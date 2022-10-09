<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use App\Models\Currency;

class Company extends BaseModel
{
	protected $table = 'companies';

	protected $default = ['xid'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'currency_id', 'warehouse_id', 'created_at', 'updated_at'];

	protected $appends = ['xid', 'x_currency_id', 'x_warehouse_id', 'light_logo_url', 'dark_logo_url', 'small_light_logo_url', 'small_dark_logo_url', 'beep_audio_url'];

	protected $hashableGetterFunctions = [
		'getXCurrencyIdAttribute' => 'currency_id',
		'getXWarehouseIdAttribute' => 'warehouse_id',
	];

	protected $casts = [
		'warehouse_id' => Hash::class . ':hash',
		'currency_id' => Hash::class . ':hash',
	];

	public function getLightLogoUrlAttribute()
	{
		$companyLogoPath = Common::getFolderPath('companyLogoPath');

		return $this->light_logo == null ? asset('images/light.png') : Common::getFileUrl($companyLogoPath, $this->light_logo);
	}

	public function getDarkLogoUrlAttribute()
	{
		$companyLogoPath = Common::getFolderPath('companyLogoPath');

		return $this->dark_logo == null ? asset('images/dark.png') : Common::getFileUrl($companyLogoPath, $this->dark_logo);
	}

	public function getSmallDarkLogoUrlAttribute()
	{
		$companyLogoPath = Common::getFolderPath('companyLogoPath');

		return $this->small_dark_logo == null ? asset('images/small_dark.png') : Common::getFileUrl($companyLogoPath, $this->small_dark_logo);
	}

	public function getSmallLightLogoUrlAttribute()
	{
		$companyLogoPath = Common::getFolderPath('companyLogoPath');

		return $this->small_light_logo == null ? asset('images/small_light.png') : Common::getFileUrl($companyLogoPath, $this->small_light_logo);
	}

	public function getBeepAudioUrlAttribute()
	{
		$audioFilesPath = Common::getFolderPath('audioFilesPath');

		return asset($audioFilesPath . 'beep.wav');
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}
}
