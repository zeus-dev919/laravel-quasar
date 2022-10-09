<?php

namespace App\Http\Controllers\Api;

use App\Classes\LangTrans;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Lang\IndexRequest;
use App\Http\Requests\Api\Lang\StoreRequest;
use App\Http\Requests\Api\Lang\UpdateRequest;
use App\Http\Requests\Api\Lang\DeleteRequest;
use App\Models\Company;
use App\Models\Lang;
use Examyou\RestAPI\Exceptions\ApiException;

class LangsController extends ApiBaseController
{
	protected $model = Lang::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function stored(Lang $lang)
	{
		LangTrans::seedAllModulesTranslations();
	}

	public function updated(Lang $lang)
	{
		LangTrans::seedAllModulesTranslations();
	}

	public function updating(Lang $lang)
	{
		if ($lang->key == 'en') {
			throw new ApiException('English language cannot be edited');
		}

		return $lang;
	}

	public function destroying(Lang $lang)
	{
		if ($lang->key == 'en') {
			throw new ApiException('English language cannot be deleted');
		}

		if ($lang->id == $this->company->lang_id) {
			$enLang = Lang::where('key', 'en')->first();
			$this->company->lang_id = $enLang->id;
			$this->company->save();
		}

		return $lang;
	}
}
