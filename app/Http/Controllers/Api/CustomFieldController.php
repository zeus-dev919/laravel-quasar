<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\CustomField\IndexRequest;
use App\Http\Requests\Api\CustomField\StoreRequest;
use App\Http\Requests\Api\CustomField\UpdateRequest;
use App\Http\Requests\Api\CustomField\DeleteRequest;
use App\Models\CustomField;

class CustomFieldController extends ApiBaseController
{
	protected $model = CustomField::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;
}
