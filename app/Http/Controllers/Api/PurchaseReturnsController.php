<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\PurchaseReturn\IndexRequest;
use App\Http\Requests\Api\PurchaseReturn\StoreRequest;
use App\Http\Requests\Api\PurchaseReturn\UpdateRequest;
use App\Http\Requests\Api\PurchaseReturn\DeleteRequest;
use App\Models\Order;
use App\Traits\OrderTraits;

class PurchaseReturnsController extends ApiBaseController
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

		$this->orderType = "purchase-returns";
	}
}
