<?php

namespace App\Http\Requests\Api\Purchase;

use Illuminate\Foundation\Http\FormRequest;

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
		$loggedUser = auth('api')->user();

		$rules = [
			'user_id' => 'required',
			'order_status' => 'required',
			'product_items'	=> 'required',
			'order_date'	=> 'required',
		];

		if ($this->invoice_number && $this->invoice_number != '') {
			$rules['invoice_number'] = 'required|unique:orders,invoice_number';
		}

		return $rules;
	}
}
