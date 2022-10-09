<?php

namespace App\Http\Requests\Api\OrderPayment;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class UpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$convertedId = Hashids::decode($this->order_id);
		$orderId = $convertedId[0];

		$convertedOrderPaymentId = Hashids::decode($this->order_payment);
		$orderPaymentId = $convertedOrderPaymentId[0];

		$orderPayment = OrderPayment::find($orderPaymentId);
		$order = Order::find($orderId);
		$dueAmount = $order->due_amount + $orderPayment->amount;

		$rules = [
			'date'    => 'required',
			'payment_mode_id'    => 'required',
			'amount'    => 'required|numeric|lte:' . $dueAmount,
			'payment_receipt'    => 'mimes:jpeg,png,jpg,pdf',
		];

		return $rules;
	}
}
