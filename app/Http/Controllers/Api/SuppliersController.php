<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Supplier\IndexRequest;
use App\Http\Requests\Api\Supplier\StoreRequest;
use App\Http\Requests\Api\Supplier\UpdateRequest;
use App\Http\Requests\Api\Supplier\DeleteRequest;
use App\Models\Supplier;
use App\Traits\PartyTraits;

class SuppliersController extends ApiBaseController
{
	use PartyTraits;

	protected $model = Supplier::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function __construct()
	{
		parent::__construct();

		$this->userType = "suppliers";
	}
}
