<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Role\IndexRequest;
use App\Http\Requests\Api\Role\StoreRequest;
use App\Http\Requests\Api\Role\UpdateRequest;
use App\Http\Requests\Api\Role\DeleteRequest;
use App\Models\Role;
use Examyou\RestAPI\Exceptions\ApiException;

class RolesController extends ApiBaseController
{
	protected $model = Role::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function stored(Role $role)
	{
		return $this->saveAndUpdatePermissions($role);
	}


	public function updated(Role $role)
	{
		return $this->saveAndUpdatePermissions($role);
	}

	public function updating(Role $role)
	{
		if ($role->name == 'admin') {
			throw new ApiException('Admin role cannot be edited');
		}

		return $role;
	}

	public function destroying(Role $role)
	{
		if ($role->name == 'admin') {
			throw new ApiException('Admin role cannot be deleted');
		}

		return $role;
	}

	public function saveAndUpdatePermissions($role)
	{
		$request = request();

		if ($request->has('permissions')) {
			$permissions = [];
			$allPermissions = $request->permissions;

			foreach ($allPermissions as $allPermission) {
				$permissions[] = $this->getIdFromHash($allPermission);
			}
			// dd($role);
			$role->savePermissions($permissions);
		}

		return $role;
	}
}
