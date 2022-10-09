<?php

namespace App\Http\Requests\Api\Settings;

use Illuminate\Foundation\Http\FormRequest;

class EmailUpdateRequest extends FormRequest
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
			'mail_driver'    => 'required',
		];

		if ($this->mail_driver == 'mail' || $this->mail_driver == 'smtp') {
			$rules['from_name'] = 'required';
			$rules['from_email'] = 'required|email';

			if ($this->mail_driver == 'smtp') {
				$rules['host'] = 'required';
				$rules['port'] = 'required';
				$rules['username'] = 'required';
				$rules['password'] = 'required';
				$rules['enable_mail_queue'] = 'required';
			}
		}

		return $rules;
	}
}
