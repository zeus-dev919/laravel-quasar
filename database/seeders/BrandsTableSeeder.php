<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */

	public function run()
	{
		Model::unguard();

		DB::table('brands')->delete();

		DB::statement('ALTER TABLE brands AUTO_INCREMENT = 1');

		$brands =  [
			[
				"name" => "Puma",
				"slug" => "puma",
			],
			[
				"name" => "Reebok",
				"slug" => "reebok",
			],
			[
				"name" => "Allen Solly",
				"slug" => "allen-solly",
			],
			[
				"name" => "Red Chief",
				"slug" => "red-chief",
			],
			[
				"name" => "Lee Cooper",
				"slug" => "lee-cooper",
			],
			[
				"name" => "Nike",
				"slug" => "nike",
			],
			[
				"name" => "Lee",
				"slug" => "lee",
			],
			[
				"name" => "Samsung",
				"slug" => "samsung",
			],
			[
				"name" => "LG",
				"slug" => "lg",
			],
			[
				"name" => "Godrej",
				"slug" => "godrej",
			],
			[
				"name" => "Voltas",
				"slug" => "voltas",
			],
			[
				"name" => "Parle-G",
				"slug" => "parle-g",
			],
			[
				"name" => "Apple",
				"slug" => "apple",
			],
			[
				"name" => "Realme",
				"slug" => "realme",
			],
			[
				"name" => "Vivo",
				"slug" => "vivo",
			],
			[
				"name" => "RX",
				"slug" => "rx",
			],
			[
				"name" => "pampers",
				"slug" => "pampers",
			],
			[
				"name" => "ARG Clothes",
				"slug" => "arg-clothes",
			],
			[
				"name" => "Himalaya",
				"slug" => "himalaya",
			],
			[
				"name" => "Crompton",
				"slug" => "crompton",
			],
			[
				"name" => "Heniz",
				"slug" => "heniz",
			],
			[
				"name" => "TATA",
				"slug" => "tata",
			],
			[
				"name" => "Fortune",
				"slug" => "fortune",
			]
		];

		foreach ($brands as $brand) {
			$newBrand = new Brand();
			$newBrand->name = $brand['name'];
			$newBrand->slug = $brand['slug'];
			$newBrand->save();
		}
	}
}
