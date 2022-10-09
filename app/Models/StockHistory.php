<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class StockHistory extends BaseModel
{
	protected $table = 'stock_history';

	protected $default = ['xid'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'warehouse_id', 'product_id', 'created_by', 'updated_at', 'pivot'];

	protected $appends = ['xid', 'x_warehouse_id', 'x_product_id', 'x_created_by'];

	protected $filterable = ['id', 'product_id'];

	protected $hashableGetterFunctions = [
		'getXWarehouseIdAttribute' => 'warehouse_id',
		'getXProductIdAttribute' => 'product_id',
		'getXCreatedByAttribute' => 'created_by',
	];

	protected $casts = [
		'warehouse_id' => Hash::class . ':hash',
		'product_id' => Hash::class . ':hash',
		'created_by' => Hash::class . ':hash',
	];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('current_warehouse', function (Builder $builder) {
			$warehouse = warehouse();

			if ($warehouse) {
				$builder->where('stock_history.warehouse_id', $warehouse->id);
			}
		});
	}

	public function product()
	{
		return $this->hasOne(Product::class, 'id', 'product_id');
	}
}
