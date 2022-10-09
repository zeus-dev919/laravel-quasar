<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use App\Classes\Common;
use App\Models\Company;
use App\Models\Warehouse;

if (!function_exists('company')) {

	/**
	 * Return currently logged in user
	 */
	function company()
	{
		if (session()->has('company')) {
			return session('company');
		}

		$company = Company::with('warehouse')->first();

		if ($company) {
			session(['company' => $company]);
			return session('company');
		}

		return null;
	}
}

if (!function_exists('user')) {

	/**
	 * Return currently logged in user
	 */
	function user()
	{
		if (session()->has('user')) {
			return session('user');
		}

		$user = auth('api')->user();

		if ($user) {
			$user = $user->load('role', 'role.perms', 'warehouse');

			session(['user' => $user]);
			return session('user');
		}

		return null;
	}
}

if (!function_exists('warehouse')) {

	/**
	 * Return currently logged in user
	 */
	function warehouse()
	{
		if (session()->has('warehouse')) {
			return session('warehouse');
		}

		$user = user();

		if ($user) {
			session(['warehouse' => $user->warehouse]);
			return session('warehouse');
		}

		return null;
	}
}
