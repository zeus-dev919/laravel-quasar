<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\StockHistory\IndexRequest;
use App\Models\StockHistory;

class StockHistoryController extends ApiBaseController
{
	protected $model = StockHistory::class;

	protected $indexRequest = IndexRequest::class;
}
