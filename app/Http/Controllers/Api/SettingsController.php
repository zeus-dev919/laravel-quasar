<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Settings\StorageIndexRequest;
use App\Http\Requests\Api\Settings\StorageUpdateRequest;
use App\Http\Requests\Api\Settings\EmailIndexRequest;
use App\Http\Requests\Api\Settings\EmailUpdateRequest;
use App\Http\Requests\Api\Settings\TestEmailRequest;
use App\Models\Settings;
use App\Notifications\TestMail;
use Examyou\RestAPI\ApiResponse;
use Examyou\RestAPI\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SettingsController extends ApiBaseController
{

	public function getStorage(StorageIndexRequest $request)
	{
		$storageSetting = Settings::where('setting_type', 'storage')->where('status', 1)->first();

		$storageSettingData = [
			'filesystem' => $storageSetting->name_key,
			'key' => $storageSetting->name_key == 'aws' ? $storageSetting->credentials['key'] : '',
			'secret' => $storageSetting->name_key == 'aws' ? $storageSetting->credentials['secret'] : '',
			'region' => $storageSetting->name_key == 'aws' && $storageSetting->credentials['region'] != '' ? $storageSetting->credentials['region'] : null,
			'bucket' => $storageSetting->name_key == 'aws' ? $storageSetting->credentials['bucket'] : '',
		];

		return ApiResponse::make(
			'Success',
			[
				'storage' => $storageSettingData,
				'regions' => Settings::$awsRegions
			]
		);
	}

	public function updateStorage(StorageUpdateRequest $request)
	{
		// Not Allowed in Demo Mode
		if (env('APP_ENV') == 'production') {
			throw new ApiException('Not Allowed In Demo Mode');
		}

		Settings::where('setting_type', 'storage')->update([
			'status' => 0
		]);

		$awsStorageSettingData = [
			'key' => $request->key,
			'secret' => $request->secret,
			'region' => $request->region,
			'bucket' => $request->bucket,
			'driver' => 's3',
		];

		Settings::where('name_key', $request->filesystem)
			->update([
				'credentials' => $request->filesystem == 'aws' ? $awsStorageSettingData : [],
				'status' => 1
			]);

		return ApiResponse::make('Success', []);
	}

	public function getEmailSetting(EmailIndexRequest $request)
	{
		$emailSetting = Settings::where('setting_type', 'email')->where('status', 1)->first();

		if ($emailSetting) {
			$verified = $emailSetting->verified;
			$settingData = [
				'mail_driver' => $emailSetting->name_key,
				'from_name' => $emailSetting->credentials['from_name'],
				'from_email' => $emailSetting->credentials['from_email']
			];

			if ($emailSetting->name_key == 'smtp') {
				$settingData['host'] = $emailSetting->credentials['host'];
				$settingData['port'] = $emailSetting->credentials['port'];
				$settingData['encryption'] = $emailSetting->credentials['encryption'];
				$settingData['username'] = $emailSetting->credentials['username'];
				$settingData['password'] = $emailSetting->credentials['password'];
				$settingData['enable_mail_queue'] = isset($emailSetting->credentials['enable_mail_queue']) ? $emailSetting->credentials['enable_mail_queue'] : 'no';
			}
		} else {
			$settingData = [
				'mail_driver' => 'none',
			];
			$verified = '';
		}

		$sendMailSettings = Settings::where('setting_type', 'send_mail_settings')
			->where('name_key', 'warehouse')
			->first();

		return ApiResponse::make(
			'Success',
			[
				'settings' => $settingData,
				'verified' => $verified,
				'sendMailSettings' => $sendMailSettings,
			]
		);
	}

	public function updateEmailSetting(EmailUpdateRequest $request)
	{
		$mailDriver = $request->mail_driver;
		Settings::where('setting_type', 'email')->update([
			'status' => 0
		]);
		$response = [
			'status' => 'success',
			'message' => 'Mail Setting saved successfully',
			'verification' => false
		];

		if ($mailDriver == "mail" || $mailDriver == "smtp") {
			$settingData = [
				'from_name' => $request->from_name,
				'from_email' => $request->from_email
			];

			if ($mailDriver == 'smtp') {
				$settingData['host'] = $request->host;
				$settingData['port'] = $request->port;
				$settingData['encryption'] = $request->has('encryption') ? $request->encryption : null;
				$settingData['username'] = $request->username;
				$settingData['password'] = $request->password;
				$settingData['enable_mail_queue'] = $request->enable_mail_queue;
			}

			$setting = Settings::where('name_key', $mailDriver)->first();
			$setting->credentials = $settingData;
			$setting->status = 1;
			$setting->save();

			$response = $setting->verifySmtp();
		}


		return ApiResponse::make('Success', $response);
	}

	public function sendMailSettings(Request $request)
	{
		$nameKey = $request->name_key;
		$sendMailSettings = $request->settings;

		$setting = Settings::where('setting_type', 'send_mail_settings')
			->where('name_key', $nameKey)
			->first();
		$setting->credentials = $sendMailSettings;
		$setting->save();

		return ApiResponse::make('Success', []);
	}

	public function sendTestMail(TestEmailRequest $request)
	{
		$email = $request->email;

		$setting = Settings::where('setting_type', 'email')->where('status', 1)->first();
		$response = $setting->verifySmtp();

		if ($response['status'] == 'success') {
			config(['queue.default' => 'sync']);

			Notification::route('mail', $email)->notify(new TestMail());

			return ApiResponse::make('Success', [
				'status' => 'success',
				'message' => 'Mail send successfully',
			]);
		}

		return ApiResponse::make('Error', $response);
	}
}
