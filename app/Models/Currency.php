<?php

namespace App\Models;

use App\Models\BaseModel;

class Currency extends BaseModel
{
	protected $table = 'currencies';

	protected $default = ['xid', 'name', 'symbol'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $hidden = ['id'];

	protected $appends = ['xid'];

	protected $filterable = ['name'];
}
