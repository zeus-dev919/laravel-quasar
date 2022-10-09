<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		if (env('APP_ENV') != 'envato') {
			// $this->call(BrandsTableSeeder::class);
			// $this->call(CategoryTableSeeder::class);
			// $this->call(ProductTableSeeder::class);
		}
	}
}
