<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Payments\IndexRequest;
use App\Http\Requests\Api\Payments\StoreRequest;
use App\Http\Requests\Api\Payments\UpdateRequest;
use App\Http\Requests\Api\Payments\DeleteRequest;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\User;
use Examyou\RestAPI\ApiResponse;
use Examyou\RestAPI\Exceptions\ApiException;
use Examyou\RestAPI\Exceptions\ResourceNotFoundException;
use Vinkla\Hashids\Facades\Hashids;

class PaymentController extends ApiBaseController
{
	protected $model = Payment::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	protected function modifyIndex($query)
	{
		$request = request();

		// Dates Filters
		if ($request->has('dates') && $request->dates != "") {
			$dates = explode(',', $request->dates);
			$startDate = $dates[0];
			$endDate = $dates[1];

			$query = $query->whereRaw('payments.date >= ?', [$startDate])
				->whereRaw('payments.date <= ?', [$endDate]);
		}

		if ($request->has('payment_mode') && $request->payment_mode != "") {
			$cashMode = PaymentMode::where('name', "Cash")->first();

			if ($cashMode && $request->payment_mode == "cash") {
				$query = $query->where('payment_mode_id', $cashMode->id);
			} else if ($cashMode && $request->payment_mode == "bank") {
				$query = $query->where('payment_mode_id', '!=', $cashMode->id);
			}
		}

		return $query;
	}

	public function storing(Payment $payment)
	{
		$request = request();
		$warehouse = warehouse();

		if ($request->has('payment_number') && $request->payment_number != "") {
			$payment->payment_number = $request->payment_number;
		}

		$payment->warehouse_id = $warehouse->id;

		return $payment;
	}

	public function stored(Payment $payment)
	{
		$request = request();
		$paidAmount = 0;

		if ($request->has('invoices') && count($request->invoices) > 0) {
			$invoices = $request->invoices;

			// Deleting previous invoices of payments
			OrderPayment::where('payment_id', $payment->id)->delete();

			foreach ($invoices as $invoice) {
				$newOrderPayment = new OrderPayment();
				$newOrderPayment->payment_id = $payment->id;
				$newOrderPayment->order_id = $this->getIdFromHash($invoice['order_id']);
				$newOrderPayment->amount = $invoice['amount'];
				$newOrderPayment->save();

				$paidAmount += $newOrderPayment->amount;

				// Updating user amount
				Common::updateOrderAmount($newOrderPayment->order_id);
			}
		} else {
			// No invoice means no order
			// So updating directly user amount
			Common::updateUserAmount($payment->user_id, $payment->warehouse_id);
		}

		if ($payment->payment_number == null) {
			$paymentType = 'payment-' . $payment->payment_type;
			$payment->payment_number = $this->getTransactionNumber($paymentType, $payment->id);
		}

		$payment->paid_amount = $paidAmount;
		$payment->unused_amount = $payment->amount - $paidAmount;
		$payment->save();

		// Updating Warehouse History
		Common::updateWarehouseHistory('payment', $payment, "add_edit");
	}

	public function updating(Payment $payment)
	{
		if ($payment->amount != $payment->getOriginal('amount')) {
			throw new ApiException('Amount can not be changed');
		}

		if ($payment->warehouse_id != $payment->getOriginal('warehouse_id')) {
			throw new ApiException('Warehouse can not be changed');
		}

		return $payment;
	}

	public function updated(Payment $payment)
	{
		// Updating Warehouse History
		Common::updateWarehouseHistory('payment', $payment, "add_edit");
	}

	public function destroy(...$args)
	{
		\DB::beginTransaction();

		// Geting id from hashids
		$xid = last(func_get_args());
		$convertedId = Hashids::decode($xid);
		$id = $convertedId[0];

		$this->validate();

		// Get object for update
		$this->query = call_user_func($this->model . "::query");

		/** @var Model $object */
		$object = $this->query->find($id);

		if (!$object) {
			throw new ResourceNotFoundException();
		}

		if (method_exists($this, 'destroying')) {
			$object = call_user_func([$this, 'destroying'], $object);
		}

		// Getting order_id before deleting payment
		$orderPayments = OrderPayment::select('order_id')->where('payment_id', $id)->get();
		$userId = $object->user_id;
		$warehouseId = $object->warehouseId;

		$object->delete();

		// Deleting order amount and user amount
		// After deleting payment
		if (count($orderPayments) > 0) {
			foreach ($orderPayments as $orderPayment) {
				Common::updateOrderAmount($orderPayment->order_id);
			}
		} else {
			Common::updateUserAmount($userId, $warehouseId);
		}

		$meta = $this->getMetaData(true);

		\DB::commit();

		// Updating Warehouse History
		Common::updateWarehouseHistory('payment', $object);

		if (method_exists($this, 'destroyed')) {
			call_user_func([$this, 'destroyed'], $object);
		}

		return ApiResponse::make("Resource deleted successfully", null, $meta);
	}

	public function customerSuppliers()
	{
		$users = User::select('id', 'name')
			->withoutGlobalScopes()
			->where('user_type', 'customers')
			->orWhere('user_type', 'suppliers')
			->get();

		$data = $users->toArray();

		return ApiResponse::make('Data fetched', $data);
	}

	public function userInvoices()
	{
		$request = request();
		$userId = $this->getIdFromHash($request->user_id);
		$paymentType = $request->payment_type;

		if ($paymentType == 'in') {
			$orderType = ['sales', 'purchase-returns'];
		} else {
			$orderType = ['purchases', 'sales-returns'];
		}

		$invoices = Order::where('user_id', $userId)
			->where('payment_status', '!=', 'paid')
			->whereIn('order_type', $orderType)
			->orderBy('orders.order_date')
			->get();

		$data = [
			'invoices' => $invoices,
		];

		return ApiResponse::make('Data fetched', $data);
	}
}
