<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		Schema::defaultStringLength(191);

		// For catching 404 Route not found error in vue app
		// Later in Base Controller we will disable it
		// Accroding to settings table app_debug column
		// config(['app.debug' => true]);
		// Setup in SmtpSettingsProvider
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
