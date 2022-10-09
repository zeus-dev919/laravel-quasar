<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\PaymentMode\IndexRequest;
use App\Http\Requests\Api\PaymentMode\StoreRequest;
use App\Http\Requests\Api\PaymentMode\UpdateRequest;
use App\Http\Requests\Api\PaymentMode\DeleteRequest;
use App\Models\Payment;
use App\Models\PaymentMode;
use Examyou\RestAPI\Exceptions\ApiException;

class PaymentModeController extends ApiBaseController
{
	protected $model = PaymentMode::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function stroing($paymentMode)
	{
		// Can not change cash
		if (strtolower($paymentMode->name) == 'cash') {
			throw new ApiException("Can't create payment mode with cash name");
		}

		return $paymentMode;
	}

	public function updating($paymentMode)
	{
		// Can not change cash
		if (strtolower($paymentMode->getOriginal('name')) == 'cash' || strtolower($paymentMode->name) == 'cash') {
			throw new ApiException("Don't have valid permission");
		}

		return $paymentMode;
	}

	public function destroying($paymentMode)
	{
		// Can not change cash
		if (strtolower($paymentMode->getOriginal('name')) == 'cash') {
			throw new ApiException("Don't have valid permission");
		}

		// Can not delete payment mode if it assigned to any payment
		$paymentModeCount = Payment::where('payment_mode_id', $paymentMode->id)->count();
		if ($paymentModeCount > 0) {
			throw new ApiException("Payment mode assigned to payment. Delete that first and try again.");
		}

		return $paymentMode;
	}
}
