<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;

class WarehouseStock extends BaseModel
{
	protected $table = 'warehouse_stocks';

	protected $default = ['xid'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'warehouse_id', 'product_id'];

	protected $appends = ['xid', 'x_warehouse_id', 'x_product_id'];

	protected $hashableGetterFunctions = [
		'getXWarehouseIdAttribute' => 'warehouse_id',
		'getXProductIdAttribute' => 'product_id',
	];

	protected $casts = [
		'warehouse_id' => Hash::class . ':hash',
		'product_id' => Hash::class . ':hash',
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
