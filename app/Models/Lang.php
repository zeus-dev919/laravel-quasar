<?php

namespace App\Models;

use App\Classes\Common;

class Lang extends BaseModel
{
	protected  $table = 'langs';

	protected $default = ['xid', 'name', 'key'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $hidden = ['id'];

	protected $appends = ['xid', 'image_url'];

	protected $filterable = ['name'];

	public function getImageUrlAttribute()
	{
		$langImagePath = Common::getFolderPath('langImagePath');

		return $this->image == null ? asset('images/lang.png') : Common::getFileUrl($langImagePath, $this->image);
	}

	public function translations()
	{
		return $this->hasMany(Translation::class);
	}
}
