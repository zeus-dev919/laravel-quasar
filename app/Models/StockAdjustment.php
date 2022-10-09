<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;

class StockAdjustment extends BaseModel
{
	protected $table = 'stock_adjustments';

	protected $default = ['xid'];

	protected $guarded = ['id', 'warehouse_id', 'created_by', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'warehouse_id', 'product_id', 'created_by'];

	protected $appends = ['xid', 'x_warehouse_id', 'x_product_id', 'x_created_by'];

	protected $filterable = ['warehouse_id', 'product_id'];

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

	public function product()
	{
		return $this->hasOne(Product::class, 'id', 'product_id');
	}

	public function warehouse()
	{
		return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
	}
}
