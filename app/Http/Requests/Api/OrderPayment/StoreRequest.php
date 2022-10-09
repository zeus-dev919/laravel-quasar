<?php

namespace App\Http\Requests\Api\OrderPayment;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class StoreRequest extends FormRequest
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

		$order = Order::find($orderId);

		$rules = [
			'date'    => 'required',
			'payment_mode_id'    => 'required',
			'amount'    => 'required|numeric|lte:' . $order->due_amount,
			'payment_receipt'    => 'mimes:jpeg,png,jpg,pdf',
		];

		return $rules;
	}
}
