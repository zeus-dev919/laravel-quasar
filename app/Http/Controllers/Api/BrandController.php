<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Brand\IndexRequest;
use App\Http\Requests\Api\Brand\StoreRequest;
use App\Http\Requests\Api\Brand\UpdateRequest;
use App\Http\Requests\Api\Brand\DeleteRequest;
use App\Models\Brand;

class BrandController extends ApiBaseController
{
	protected $model = Brand::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
