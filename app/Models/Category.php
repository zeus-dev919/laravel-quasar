<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use Vinkla\Hashids\Facades\Hashids;

class Category extends BaseModel
{
	protected $table = 'categories';

	protected $default = ['id', 'xid', 'name', 'slug', 'parent_id', 'x_parent_id', 'image'];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $hidden = ['id', 'parent_id'];

	protected $appends = ['xid', 'x_parent_id', 'image_url'];

	protected $filterable = ['id', 'name', 'slug', 'parent_id'];

	protected $hashableGetterFunctions = [
		'getXParentIdAttribute' => 'parent_id',
		'getXWarehouseIdAttribute' => 'warehouse_id',
	];

	protected $casts = [
		'parent_id' => Hash::class . ':hash',
	];

	public function getImageUrlAttribute()
	{
		$categoryLogoPath = Common::getFolderPath('categoryImagePath');

		return $this->image == null ? asset('images/category.png') : Common::getFileUrl($categoryLogoPath, $this->image);
	}

	public function subcategories()
	{
		return $this->hasMany(Category::class, 'parent_id', 'id');
	}

	public function childs()
	{
		return $this->subcategories()->with('childs');
	}
}
