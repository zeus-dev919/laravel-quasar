<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */

	public function run()
	{
		Model::unguard();

		DB::table('categories')->delete();

		DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

		$categories =  [
			[
				"name" => "Electronics",
				"slug" => "electronics",
				"parent_id" => null
			],
			[
				"name" => "Fashion",
				"slug" => "fashion",
				"parent_id" => null
			],
			[
				"name" => "Grocery",
				"slug" => "grocery",
				"parent_id" => null
			],
			[
				"name" => "Home and Furnitures",
				"slug" => "home-and-furnitures",
				"parent_id" => null
			],
			[
				"name" => "Baby & Kids",
				"slug" => "baby-kids",
				"parent_id" => null
			],
			[
				"name" => "Mobiles",
				"slug" => "mobiles",
				"parent_id" => 1
			],
			[
				"name" => "Televisions",
				"slug" => "televisions",
				"parent_id" => 1
			],
			[
				"name" => "Clothes",
				"slug" => "clothes",
				"parent_id" => 2
			],
			[
				"name" => "Shoes",
				"slug" => "shoes",
				"parent_id" => 2
			]
		];

		foreach ($categories as $category) {
			$newCategory = new Category();
			$newCategory->name = $category['name'];
			$newCategory->slug = $category['slug'];
			$newCategory->parent_id = $category['parent_id'];
			$newCategory->save();
		}
	}
}
