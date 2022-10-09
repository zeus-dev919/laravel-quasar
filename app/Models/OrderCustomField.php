<?php

namespace App\Models;

class OrderCustomField extends BaseModel
{
	protected  $table = 'order_custom_fields';

	public $timestamps = false;

	protected $default = ['xid', 'field_name'];

	protected $guarded = ['id'];

	protected $hidden = ['id'];

	protected $appends = ['xid'];

	protected $filterable = ['field_name'];
}
