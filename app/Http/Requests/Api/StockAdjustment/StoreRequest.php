<?php

namespace App\Http\Requests\Api\StockAdjustment;

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
		$rules = [
			'product_id'    => 'required',
			'quantity'    => 'required|numeric',
			'adjustment_type'    => 'required|in:add,subtract',
		];

		return $rules;
	}
}
