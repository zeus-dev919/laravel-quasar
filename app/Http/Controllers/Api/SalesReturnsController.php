<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\SalesReturn\IndexRequest;
use App\Http\Requests\Api\SalesReturn\StoreRequest;
use App\Http\Requests\Api\SalesReturn\UpdateRequest;
use App\Http\Requests\Api\SalesReturn\DeleteRequest;
use App\Models\Order;
use App\Traits\OrderTraits;

class SalesReturnsController extends ApiBaseController
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

		$this->orderType = "sales-returns";
	}
}
