<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */

	public function run()
	{
		Model::unguard();

		DB::table('custom_fields')->delete();
		DB::table('products')->delete();

		DB::statement('ALTER TABLE custom_fields AUTO_INCREMENT = 1');
		DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');

		$customField = new CustomField();
		$customField->name = "Expiry Date";
		$customField->type = "date";
		$customField->save();
	}
}
