<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Unit\IndexRequest;
use App\Http\Requests\Api\Unit\StoreRequest;
use App\Http\Requests\Api\Unit\UpdateRequest;
use App\Http\Requests\Api\Unit\DeleteRequest;
use App\Models\Unit;

class UnitController extends ApiBaseController
{
	protected $model = Unit::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
