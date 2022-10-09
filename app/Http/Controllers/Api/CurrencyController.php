<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Currency\IndexRequest;
use App\Http\Requests\Api\Currency\StoreRequest;
use App\Http\Requests\Api\Currency\UpdateRequest;
use App\Http\Requests\Api\Currency\DeleteRequest;
use App\Models\Currency;
use Examyou\RestAPI\Exceptions\ApiException;

class CurrencyController extends ApiBaseController
{
	protected $model = Currency::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function destroying(Currency $currency)
	{
		if ($currency->id == $this->company->currency_id) {
			throw new ApiException('Default currency cannot be deleted');
		}

		return $currency;
	}
}
