<?php

use App\Models\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('langs', function (Blueprint $table) {
			$table->id();
			$table->string('image')->nullable()->default(null);
			$table->string('name');
			$table->string('key');
			$table->boolean('enabled')->default(true);
			$table->timestamps();
		});

		$enLang = new Lang();
		$enLang->name = 'English';
		$enLang->key = 'en';
		$enLang->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('langs');
	}
}
