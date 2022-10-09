<?php

namespace App\Models;

use App\Models\BaseModel;

class Tax extends BaseModel
{
	protected $table = 'taxes';

	protected $default = ['xid', 'name', 'rate'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $filterable = ['name'];

	protected $hidden = ['id'];

	protected $appends = ['xid'];
}
