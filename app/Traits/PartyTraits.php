<?php

namespace App\Traits;

use App\Classes\Common;
use App\Models\UserDetails;
use App\Models\Warehouse;

trait PartyTraits
{
	public $userType = "";

	public function modifyIndex($query)
	{
		$request = request();
		$warehouse = warehouse();

		$query = $query->join('user_details', 'user_details.user_id', '=', 'users.id')
			->where('user_details.warehouse_id', $warehouse->id);

		if ($request->has('search_due_type') && $request->search_due_type != "") {
			if ($request->search_due_type == "receive") {
				$query = $query->where('user_details.due_amount', '>=', 0);
			} else {
				$query = $query->where('user_details.due_amount', '<=', 0);
			}
		}

		return $query;
	}

	public function storing($user)
	{
		$warehouse = warehouse();
		$user->warehouse_id = $warehouse->id;
		$user->user_type = $this->userType;

		return $user;
	}

	public function updating($user)
	{
		$user->user_type = $this->userType;

		return $user;
	}

	public function stored($user)
	{
		// Generating user details for each warehouse
		$allWarehouses = Warehouse::select('id')->get();
		foreach ($allWarehouses as $allWarehouse) {
			$userDetails = new UserDetails();
			$userDetails->warehouse_id = $allWarehouse->id;
			$userDetails->user_id = $user->id;
			$userDetails->credit_period = 30;
			$userDetails->save();
		}

		$this->storedAndUpdated($user);
	}

	public function updated($user)
	{
		$this->storedAndUpdated($user);
	}

	public function storedAndUpdated($user)
	{
		$request = request();
		$warehouse = warehouse();
		$warehouseId = $warehouse->id;

		$userDetails = $user->details;
		$userDetails->warehouse_id = $warehouseId;
		$userDetails->user_id = $user->id;
		$userDetails->opening_balance = $request->opening_balance == "" ? 0 : $request->opening_balance;
		$userDetails->opening_balance_type = $request->opening_balance_type;
		$userDetails->credit_period = $request->credit_period == "" ? 0 : $request->credit_period;
		$userDetails->credit_limit = $request->credit_limit == "" ? 0 : $request->credit_limit;
		$userDetails->save();

		Common::updateUserAmount($user->id, $warehouseId);

		return $user;
	}
};
