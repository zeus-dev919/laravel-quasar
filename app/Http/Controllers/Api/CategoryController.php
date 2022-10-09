<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Category\IndexRequest;
use App\Http\Requests\Api\Category\StoreRequest;
use App\Http\Requests\Api\Category\UpdateRequest;
use App\Http\Requests\Api\Category\DeleteRequest;
use App\Models\Category;
use App\Models\Product;
use Examyou\RestAPI\Exceptions\ApiException;

class CategoryController extends ApiBaseController
{
	protected $model = Category::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function destroying(Category $category)
	{
		// Can not delete parent category
		$childCategoryCount = Category::where('parent_id', $category->id)->count();
		if ($childCategoryCount > 0) {
			throw new ApiException('Parent category cannot be deleted. Please delete child category first.');
		}

		// Category assigned to any product will not be deleted
		$productCount = Product::where('category_id', $category->id)->count();
		if ($productCount > 0) {
			throw new ApiException('Cateogry assigned to any product is not deletable.');
		}

		return $category;
	}
}
