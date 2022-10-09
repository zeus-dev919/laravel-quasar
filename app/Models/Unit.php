<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;

class Unit extends BaseModel
{
	protected $table = 'units';

	protected $default = ['xid', 'name', 'short_name', 'operator', 'operator_value'];

	protected $guarded = ['id', 'is_deletable', 'created_at', 'updated_at'];

	protected $filterable = ['name'];

	protected $hidden = ['id', 'parent_id'];

	protected $appends = ['xid'];
}
