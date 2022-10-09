<?php

namespace App\Models;

use App\Classes\Common;
use Illuminate\Notifications\Notifiable;
use App\Models\BaseModel;

class Warehouse extends BaseModel
{
	use Notifiable;

	protected $table = 'warehouses';

	protected $default = ['xid', 'name'];

	protected $guarded = ['id', 'users', 'created_at', 'updated_at'];

	protected $hidden = ['id'];

	protected $appends = ['xid', 'logo_url', 'signature_url'];

	protected $filterable = ['name', 'email', 'phone', 'city', 'country', 'zipcode'];

	public function getLogoUrlAttribute()
	{
		$warehouseLogoPath = Common::getFolderPath('warehouseLogoPath');

		return $this->logo == null ? asset('images/warehouse.png') : Common::getFileUrl($warehouseLogoPath, $this->logo);
	}

	public function getSignatureUrlAttribute()
	{
		$warehouseLogoPath = Common::getFolderPath('warehouseLogoPath');

		return $this->signature == null ? null : Common::getFileUrl($warehouseLogoPath, $this->signature);
	}

	public function users()
	{
		return $this->belongsToMany(User::class);
	}
}
