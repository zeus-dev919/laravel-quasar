<?php

namespace App\Http\Requests\Api\Payments;

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
			'date'    => 'required',
			'payment_mode_id'    => 'required',
			'amount'    => 'required|numeric',
			'payment_receipt'    => 'mimes:jpeg,png,jpg,pdf',
		];

		return $rules;
	}
}
