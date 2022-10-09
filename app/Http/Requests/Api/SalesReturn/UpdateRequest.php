<?php

namespace App\Http\Requests\Api\SalesReturn;

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
		$convertedId = Hashids::decode($this->route('sales_return'));
		$id = $convertedId[0];

		$rules = [
			'order_date' => 'required',
			'user_id' => 'required',
			'order_status' => 'required',
			'product_items'	=> 'required',
		];

		if ($this->has('invoice_number') && $this->invoice_number != '') {
			$rules['invoice_number'] = 'required|unique:orders,invoice_number,' . $id;
		} else {
			$rules['invoice_number'] = 'required';
		}

		return $rules;
	}
}
