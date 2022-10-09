<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\WarehouseHistory\IndexRequest;
use App\Models\WarehouseHistory;

class WarehouseHistoryController extends ApiBaseController
{
	protected $model = WarehouseHistory::class;

	protected $indexRequest = IndexRequest::class;

	public function modifyIndex($query)
	{
		$request = request();

		if ($request->has('result_type') && $request->result_type = "customer_supplier") {
			$query = $query->whereIn('type', ['purchases', 'purchase-returns', 'sales', 'sales-returns', 'payment-out', 'payment-in']);
		}

		return $query;
	}
}
