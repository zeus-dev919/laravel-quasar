<?php

namespace App\Models;

use App\Casts\Hash;

class Translation extends BaseModel
{
	protected  $table = 'translations';

	protected $default = ['xid'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $filterable = ['name'];

	protected $hidden = ['id', 'lang_id'];

	protected $appends = ['xid', 'x_lang_id'];

	protected $hashableGetterFunctions = [
		'getXLangIdAttribute' => 'lang_id'
	];

	protected $casts = [
		'lang_id' => Hash::class . ':hash'
	];
}
