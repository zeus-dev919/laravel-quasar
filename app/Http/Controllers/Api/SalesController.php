<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Sales\IndexRequest;
use App\Http\Requests\Api\Sales\StoreRequest;
use App\Http\Requests\Api\Sales\UpdateRequest;
use App\Http\Requests\Api\Sales\DeleteRequest;
use App\Models\Order;
use App\Traits\OrderTraits;

class SalesController extends ApiBaseController
{
	use OrderTraits;

	protected $model = Order::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function __construct()
	{
		parent::__construct();

		$this->orderType = "sales";
	}
}
