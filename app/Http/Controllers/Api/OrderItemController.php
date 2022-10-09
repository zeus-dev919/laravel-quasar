<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\OrderItem\IndexRequest;
use App\Models\OrderItem;

class OrderItemController extends ApiBaseController
{
	protected $model = OrderItem::class;

	protected $indexRequest = IndexRequest::class;

	public function modifyIndex($query)
	{
		$request = request();
		$warehouse = warehouse();

		$query = $query->join('orders', 'orders.id', '=', 'order_items.order_id')
			->where('orders.warehouse_id', $warehouse->id);

		return $query;
	}
}
