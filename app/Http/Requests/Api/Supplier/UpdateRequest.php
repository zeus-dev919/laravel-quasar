<?php

namespace App\Http\Requests\Api\Supplier;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
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
		$convertedId = Hashids::decode($this->route('supplier'));
		$id = $convertedId[0];

		$rules = [
			'phone'    => [
				'numeric',
				Rule::unique('users', 'phone')->where(function ($query) {
					return $query->where('user_type', 'suppliers');
				})->ignore($id)
			],
			'email'    => [
				'required', 'email',
				Rule::unique('users', 'email')->where(function ($query) {
					return $query->where('user_type', 'suppliers');
				})->ignore($id)
			],
			'name'  => 'required',
			'status'     => 'required',
		];

		return $rules;
	}
}
