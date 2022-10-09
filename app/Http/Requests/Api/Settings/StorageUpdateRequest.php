<?php

namespace App\Http\Requests\Api\Settings;

use Illuminate\Foundation\Http\FormRequest;

class StorageUpdateRequest extends FormRequest
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
			'filesystem'    => 'required',
		];


		if ($this->filesystem == 'aws') {
			$rules = [
				'filesystem'    => 'required',
				'key'    => 'required',
				'secret'    => 'required',
				'region'    => 'required',
				'bucket'    => 'required',
			];
		}

		return $rules;
	}
}
