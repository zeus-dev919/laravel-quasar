<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use Vinkla\Hashids\Facades\Hashids;

class Product extends BaseModel
{
	protected $table = 'products';

	protected $default = ['xid'];

	protected $guarded = ['id', 'user_id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'category_id', 'brand_id', 'unit_id', 'user_id'];

	protected $appends = ['xid', 'x_category_id', 'x_brand_id', 'x_unit_id', 'x_user_id', 'image_url'];

	protected $filterable = ['id', 'products.id', 'name', 'category_id', 'brand_id'];

	protected $hashableGetterFunctions = [
		'getXCategoryIdAttribute' => 'category_id',
		'getXBrandIdAttribute' => 'brand_id',
		'getXUnitIdAttribute' => 'unit_id',
		'getXUserIdAttribute' => 'user_id',
	];

	protected $casts = [
		'category_id' => Hash::class . ':hash',
		'brand_id' => Hash::class . ':hash',
		'unit_id' => Hash::class . ':hash',
		'user_id' => Hash::class . ':hash',
	];

	public function getImageUrlAttribute()
	{
		$productImagePath = Common::getFolderPath('productImagePath');

		return $this->image == null ? asset('imagess/product.png') : Common::getFileUrl($productImagePath, $this->image);
	}

	public function category()
	{
		return $this->hasOne(Category::class, 'id', 'category_id');
	}

	public function brand()
	{
		return $this->hasOne(Brand::class, 'id', 'brand_id');
	}

	public function unit()
	{
		return $this->hasOne(Unit::class, 'id', 'unit_id');
	}

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function warehouseStocks()
	{
		return $this->hasMany(WarehouseStock::class, 'product_id', 'id');
	}

	public function items()
	{
		return $this->hasMany(OrderItem::class, 'product_id', 'id');
	}

	public function stockHistory()
	{
		return $this->hasMany(StockHistory::class, 'product_id', 'id');
	}

	public function customFields()
	{
		return $this->hasMany(ProductCustomField::class, 'product_id', 'id');
	}

	public function details()
	{
		return $this->hasOne(ProductDetails::class);
	}

	// Start - Hashable Getter Functions
	// public function getXCategoryIdAttribute()
	// {
	// 	$value = $this->category_id;

	// 	if ($value) {
	// 		$value = Hashids::encode($value);
	// 	}

	// 	return $value;
	// }

	// public function getXBrandIdAttribute()
	// {
	// 	$value = $this->brand_id;

	// 	if ($value) {
	// 		$value = Hashids::encode($value);
	// 	}

	// 	return $value;
	// }

	// public function getXTaxIdAttribute()
	// {
	// 	$value = $this->tax_id;

	// 	if ($value) {
	// 		$value = Hashids::encode($value);
	// 	}

	// 	return $value;
	// }

	// public function getXUnitIdAttribute()
	// {
	// 	$value = $this->unit_id;

	// 	if ($value) {
	// 		$value = Hashids::encode($value);
	// 	}

	// 	return $value;
	// }

	// public function getXUserIdAttribute()
	// {
	// 	$value = $this->user_id;

	// 	if ($value) {
	// 		$value = Hashids::encode($value);
	// 	}

	// 	return $value;
	// }

	// End - Hashable Getter Functions
}
