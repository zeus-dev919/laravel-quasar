<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Classes\Notify;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\StockAdjustment\IndexRequest;
use App\Http\Requests\Api\StockAdjustment\StoreRequest;
use App\Http\Requests\Api\StockAdjustment\UpdateRequest;
use App\Http\Requests\Api\StockAdjustment\DeleteRequest;
use App\Models\StockAdjustment;
use App\Models\StockHistory;
use Examyou\RestAPI\Exceptions\ApiException;

class StockAdjustmentController extends ApiBaseController
{

	protected $model = StockAdjustment::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function modifyIndex($query)
	{
		$warehouse = warehouse();

		// Get only current warehouse stocks
		$query = $query->where('stock_adjustments.warehouse_id', $warehouse->id);

		return $query;
	}

	public function storing(StockAdjustment $stockAdjustment)
	{
		$warehouse = warehouse();
		$stockAdjustment->created_by = auth('api')->user()->id;
		$stockAdjustment->warehouse_id = $warehouse->id;

		// action_type will be either add_add or add_subtract For Adding Stock Adjustment
		$stockHistory = new StockHistory();
		$stockHistory->warehouse_id = $stockAdjustment->warehouse_id;
		$stockHistory->product_id = $stockAdjustment->product_id;
		$stockHistory->quantity = $stockAdjustment->quantity;
		$stockHistory->old_quantity = 0;
		$stockHistory->order_type = 'stock_adjustment';
		$stockHistory->stock_type = $stockAdjustment->adjustment_type == 'add' ? 'in' : 'out';
		$stockHistory->action_type = "add_" . $stockAdjustment->adjustment_type;
		$stockHistory->created_by = auth('api')->user()->id;
		$stockHistory->save();

		return $stockAdjustment;
	}

	public function updating(StockAdjustment $stockAdjustment)
	{
		$loggedUser = user();
		$oldStockAdjustment = StockAdjustment::find($stockAdjustment->id);
		$stockAdjustment->created_by = $loggedUser->id;

		// action_type will be either edit_$oldAdjustment_$newAdjustment For Updating Stock Adjustment
		if ($oldStockAdjustment->quantity != $stockAdjustment->quantity) {

			if ($oldStockAdjustment->adjustment_type == "add" && $stockAdjustment->adjustment_type == "add") {
				$stockType = "in";
			} else if ($oldStockAdjustment->adjustment_type == "subtract" && $stockAdjustment->adjustment_type == "subtract") {
				$stockType = "out";
			} else if ($oldStockAdjustment->adjustment_type == "add" && $stockAdjustment->adjustment_type == "subtract") {
				$stockType = "out";
			} else {
				$stockType = "in";
			}

			$stockHistory = new StockHistory();
			$stockHistory->warehouse_id = $stockAdjustment->warehouse_id;
			$stockHistory->product_id = $stockAdjustment->product_id;
			$stockHistory->quantity = $stockAdjustment->quantity;
			$stockHistory->old_quantity = $oldStockAdjustment->quantity;
			$stockHistory->order_type = 'stock_adjustment';
			$stockHistory->stock_type = $stockType;
			$stockHistory->action_type = "edit_" . $oldStockAdjustment->adjustment_type . "_" . $stockAdjustment->adjustment_type;
			$stockHistory->created_by = auth('api')->user()->id;
			$stockHistory->save();
		}


		return $stockAdjustment;
	}

	public function stored(StockAdjustment $stockAdjustment)
	{
		Common::recalculateOrderStock($stockAdjustment->warehouse_id, $stockAdjustment->product_id);

		// Notifying to Warehouse
		Notify::send('stock_adjustment_create', $stockAdjustment);
	}

	public function updated(StockAdjustment $stockAdjustment)
	{
		Common::recalculateOrderStock($stockAdjustment->warehouse_id, $stockAdjustment->product_id);

		// Notifying to Warehouse
		Notify::send('stock_adjustment_update', $stockAdjustment);
	}

	public function destroying(StockAdjustment $stockAdjustment)
	{
		$loggedUser = auth('api')->user();

		// If logged in user is not admin
		// then cannot delete order who are 
		// of other warehouse
		if (!$loggedUser->hasRole('admin') && $stockAdjustment->warehouse_id != $loggedUser->warehouse_id) {
			throw new ApiException("Don't have valid permission");
		}

		$stockHistory = new StockHistory();
		$stockHistory->warehouse_id = $stockAdjustment->warehouse_id;
		$stockHistory->product_id = $stockAdjustment->product_id;
		$stockHistory->quantity = 0;
		$stockHistory->old_quantity = $stockAdjustment->quantity;
		$stockHistory->order_type = 'stock_adjustment';
		$stockHistory->stock_type = $stockAdjustment->adjustment_type == 'add' ? 'in' : 'out';
		$stockHistory->action_type = "delete_" . $stockAdjustment->adjustment_type;
		$stockHistory->created_by = auth('api')->user()->id;
		$stockHistory->save();

		Common::recalculateOrderStock($stockAdjustment->warehouse_id, $stockAdjustment->product_id);


		return $stockAdjustment;
	}

	public function destroyed(StockAdjustment $stockAdjustment)
	{
		Common::recalculateOrderStock($stockAdjustment->warehouse_id, $stockAdjustment->product_id);

		// Notifying to Warehouse
		Notify::send('stock_adjustment_delete', $stockAdjustment);
	}
}
