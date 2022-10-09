<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Tax\IndexRequest;
use App\Http\Requests\Api\Tax\StoreRequest;
use App\Http\Requests\Api\Tax\UpdateRequest;
use App\Http\Requests\Api\Tax\DeleteRequest;
use App\Models\Tax;

class TaxController extends ApiBaseController
{
	protected $model = Tax::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
