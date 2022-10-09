<?php

namespace App\Http\Controllers;

use Examyou\RestAPI\ApiController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\File;
use App\Classes\Common;
use App\Models\Company;
use App\Models\Settings;
use Vinkla\Hashids\Facades\Hashids;

class ApiBaseController extends ApiController
{
	use AuthorizesRequests, DispatchesJobs;

	public $company = [];

	public function __construct()
	{
		parent::__construct();

		$this->company = Company::first();

		$this->middleware(function ($request, $next) {

			// Mail and config settings will we set by SmtpSettingsProvider
			$this->setStorageSettings();

			return $next($request);
		});
	}

	public function setStorageSettings()
	{
		$storageSetting = Settings::where('setting_type', 'storage')->where('status', 1)->first();

		$storageName = $storageSetting && $storageSetting->name_key == 'aws' ? 's3' : 'local';

		config(['filesystems.default' => $storageName]);

		if ($storageName == 's3') {
			config([
				'filesystems.disks.s3.key' => $storageSetting->credentials['key'],
				'filesystems.disks.s3.secret' => $storageSetting->credentials['secret'],
				'filesystems.disks.s3.region' => $storageSetting->credentials['region'],
				'filesystems.disks.s3.bucket' => $storageSetting->credentials['bucket'],
			]);
		}
	}

	public function getTransactionNumber($type, $number)
	{
		$prefixs = [
			'payment-in' => 'PAY-IN-',
			'payment-out' => 'PAY-IN-',
			'sales' => 'SALE-',
			'purchase' => 'PUR-',
		];

		return $prefixs[$type] . $number;
	}

	public function getIdFromHash($hash)
	{
		return Common::getIdFromHash($hash);
	}

	public function getHashFromId($id)
	{
		return Common::getHashFromId($id);
	}

	public function getIdArrayFromHash($values)
	{
		$convertedArray = [];

		foreach ($values as $value) {
			$convertedArray[] = $this->getIdFromHash($value);
		}

		return $convertedArray;
	}

	public function getHashArrayFromId($values)
	{
		$convertedArray = [];

		foreach ($values as $value) {
			$convertedArray[] = $this->getHashFromId($value);
		}

		return $convertedArray;
	}

	public function errorMessageResponse($message)
	{
		return response()->json([
			'code' => 403,
			'message' => $message
		], 403);
	}
}
