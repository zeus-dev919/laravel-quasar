<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Permission\IndexRequest;
use App\Models\Permission;

class PermissionController extends ApiBaseController
{
	protected $model = Permission::class;

	protected $indexRequest = IndexRequest::class;
}
