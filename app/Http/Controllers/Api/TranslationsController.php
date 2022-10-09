<?php

namespace App\Http\Controllers\Api;

use App\Classes\LangTrans;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Translation\IndexRequest;
use App\Http\Requests\Api\Translation\StoreRequest;
use App\Http\Requests\Api\Translation\UpdateRequest;
use App\Http\Requests\Api\Translation\DeleteRequest;
use App\Models\Translation;
use Examyou\RestAPI\ApiResponse;

class TranslationsController extends ApiBaseController
{
	protected $model = Translation::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function refetchTranslations()
	{
		LangTrans::seedAllModulesTranslations();

		return ApiResponse::make(
			'Success',
			[]
		);
	}
}
