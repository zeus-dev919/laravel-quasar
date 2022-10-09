<?php

use App\Models\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taxes', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->float('rate', 8, 2);
			$table->timestamps();
		});

		$tax = new Tax();
		$tax->name = "GST";
		$tax->rate = 18;
		$tax->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('taxes');
	}
}
