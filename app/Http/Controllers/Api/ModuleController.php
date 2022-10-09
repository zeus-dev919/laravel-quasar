<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Classes\LangTrans;
use App\Http\Controllers\ApiBaseController;
use Examyou\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use ZanySoft\Zip\Zip;

class ModuleController extends ApiBaseController
{
	public function index()
	{
		$modulesData = Common::moduleInformations();

		return ApiResponse::make('Data fetched', $modulesData);
	}

	public function updateStatus(Request $request)
	{
		$moduleName = $request->verified_name;
		$checked = $request->checked;

		$module = Module::find($moduleName);

		if ($checked) {
			$module->enable();

			Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
		} else {
			$module->disable();
		}

		$modulesData = Common::moduleInformations();

		return ApiResponse::make('Success', $modulesData);
	}

	public function install(Request $request)
	{
		$response = Http::post('https://envato.codeifly.com/install', [
			'verified_name' => $request->verified_name,
			'domain' => $request->domain,
		]);

		$responseData = $response->object();

		$tempPath = storage_path() . '/app';
		$fileName = $request->verified_name . '.zip';
		$tempFileName = $tempPath . '/' . $fileName;

		$fileHandler = fopen($tempFileName, 'w');

		$fileUrl = $responseData->url;

		$client = new Client();
		$client->request('GET', $fileUrl,  [
			'sink' => $fileHandler,
			'progress' => function ($downloadTotalSize, $downloadTotalSoFar, $uploadTotalSize, $uploadSizeSoFar) {
				$percentageDownloaded = ($downloadTotalSize > 0) ? (($downloadTotalSoFar / $downloadTotalSize) * 100) : 0;
				File::put(public_path() . '/download-percentage.txt', $percentageDownloaded);
			},
			'verify' => false
		]);

		$modulesData = Common::moduleInformations();

		return ApiResponse::make('Success', $modulesData);
	}

	public function extractZip(Request $request)
	{
		$moduleName = $request->verified_name;

		$tempPath = storage_path() . '/app';
		$fileName = $request->verified_name . '.zip';
		$tempFileName = $tempPath . '/' . $fileName;

		$extractPath = base_path() . '/Modules';

		$zip = Zip::open($tempFileName);
		$zip->extract($extractPath);

		LangTrans::seedTranslations($moduleName);
		sleep(3);
		Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);

		// Delete Downloaded File

		$modulesData = Common::moduleInformations();

		return ApiResponse::make('Success', [
			'installed_modules' => $modulesData['installed_modules'],
			'enabled_modules' => Arr::pluck($modulesData['enabled_modules'], 'verified_name'),
			'verified_name' => $moduleName,
			'version'	=> $this->getModuleVersion($moduleName)
		]);
	}

	public function getModuleVersion($moduleName)
	{
		$module = Module::find($moduleName);

		if ($module) {
			$modulePath = $module->getPath();
			$version = File::get($modulePath . '/version.txt');

			return preg_replace("/\r|\n/", "", $version);
		}

		return "-";
	}

	public function downloadPercent(Request $request)
	{
		$percentage = File::get(public_path() . '/download-percentage.txt');

		return ApiResponse::make('Success', [
			'percentage' => $percentage
		]);
	}
}
