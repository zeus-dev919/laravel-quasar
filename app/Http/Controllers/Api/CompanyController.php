<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Company\UpdateRequest;
use App\Models\Company;
use Examyou\RestAPI\Exceptions\ApiException;

class CompanyController extends ApiBaseController
{
	protected $model = Company::class;

	protected $updateRequest = UpdateRequest::class;

	public function updating(Company $company)
	{
		if (env('APP_ENV') == 'production' && ($company->isDirty('name') ||
			$company->isDirty('short_name') || $company->isDirty('light_logo') ||
			$company->isDirty('dark_logo') || $company->isDirty('small_dark_logo') ||
			$company->isDirty('small_light_logo') || $company->isDirty('app_debug') ||
			$company->isDirty('update_app_notification') || $company->isDirty('app_debug')
		)) {
			throw new ApiException('Not Allowed In Demo Mode');
		}

		return $company;
	}
}
