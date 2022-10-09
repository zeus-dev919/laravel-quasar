<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Customer::observe(CustomerObserver::class);
		// User::observe(UserObserver::class);
		// Supplier::observe(SupplierObserver::class);
	}
}
