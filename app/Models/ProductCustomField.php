<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ProductCustomField extends BaseModel
{
	protected  $table = 'product_custom_fields';

	public $timestamps = false;

	protected $default = ['xid', 'field_name'];

	protected $guarded = ['id'];

	protected $hidden = ['id'];

	protected $appends = ['xid'];

	protected $filterable = ['field_name'];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('current_warehouse', function (Builder $builder) {
			$warehouse = warehouse();

			if ($warehouse) {
				$builder->where('product_custom_fields.warehouse_id', $warehouse->id);
			}
		});
	}
}
