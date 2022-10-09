<?php

namespace App\Http\Requests\Api\Company;

use Illuminate\Foundation\Http\FormRequest;

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

		$rules = [
			'name'    => 'required',
			'short_name'    => 'required',
			'email'    => 'required|email',
			'phone'    => 'required|integer',
			'currency_id' => 'required',
			'warehouse_id' => 'required',
			'timezone' => 'required',
			'date_format' => 'required',
			'time_format' => 'required',
		];

		return $rules;
	}
}
