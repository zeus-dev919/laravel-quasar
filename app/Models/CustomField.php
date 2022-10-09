<?php

namespace App\Models;

class CustomField extends BaseModel
{
	protected  $table = 'custom_fields';

	public $timestamps = false;

	protected $default = ['xid', 'name'];

	protected $guarded = ['id'];

	protected $hidden = ['id'];

	protected $appends = ['xid'];

	protected $filterable = ['name'];
}
