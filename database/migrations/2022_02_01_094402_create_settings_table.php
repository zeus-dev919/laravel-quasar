<?php

use App\Classes\NotificationSeed;
use App\Models\Settings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function (Blueprint $table) {
			$table->id();
			$table->string('setting_type');
			$table->string('name');
			$table->string('name_key');
			$table->json('credentials')->nullable()->default(null);
			$table->json('other_data')->nullable()->default(null);
			$table->boolean('status')->default(false);
			$table->boolean('verified')->default(false);
			$table->timestamps();
		});

		$local = new Settings();
		$local->setting_type = 'storage';
		$local->name = 'Local';
		$local->name_key = 'local';
		$local->status = true;
		$local->save();

		$aws = new Settings();
		$aws->setting_type = 'storage';
		$aws->name = 'AWS';
		$aws->name_key = 'aws';
		$aws->credentials = [
			'driver' => 's3',
			'key' => '',
			'secret' => '',
			'region' => '',
			'bucket' => '',

		];
		$aws->save();

		$smtp = new Settings();
		$smtp->setting_type = 'email';
		$smtp->name = 'SMTP';
		$smtp->name_key = 'smtp';
		$smtp->credentials = [
			'from_name' => '',
			'from_email' => '',
			'host' => '',
			'port' => '',
			'encryption' => '',
			'username' => '',
			'password' => '',

		];
		$smtp->save();

		$sendMailSettings = new Settings();
		$sendMailSettings->setting_type = 'send_mail_settings';
		$sendMailSettings->name = 'Send mail to warehouse';
		$sendMailSettings->name_key = 'warehouse';
		$sendMailSettings->credentials = [];
		$sendMailSettings->save();

		NotificationSeed::seedAllModulesNotifications();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('settings');
	}
}
