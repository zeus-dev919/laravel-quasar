<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Validation\Rule;
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
		$loggedUser = auth('api')->user();
		$convertedId = Hashids::decode($this->route('user'));
		$id = $convertedId[0];

		$rules = [
			'phone'    => [
				'numeric',
				Rule::unique('users', 'phone')->where(function ($query) {
					return $query->where('user_type', 'users');
				})->ignore($id)
			],
			'name' => 'required',
			'email'    => [
				'required', 'email',
				Rule::unique('users', 'email')->where(function ($query) {
					return $query->where('user_type', 'users');
				})->ignore($id)
			],
			'status' => 'required',
		];

		if ($loggedUser->hasRole('admin')) {
			$rules['role_id'] = 'required';
		}

		if ($this->password != '') {
			$rules['password'] = 'required|min:8';
		}

		return $rules;
	}
}
