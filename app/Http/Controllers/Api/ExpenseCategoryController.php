<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ExpenseCategory\IndexRequest;
use App\Http\Requests\Api\ExpenseCategory\StoreRequest;
use App\Http\Requests\Api\ExpenseCategory\UpdateRequest;
use App\Http\Requests\Api\ExpenseCategory\DeleteRequest;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends ApiBaseController
{
	protected $model = ExpenseCategory::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
