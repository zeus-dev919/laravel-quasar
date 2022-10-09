<?php

use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('warehouses', function (Blueprint $table) {
			$table->id();
			$table->string('logo')->nullable()->default(null);
			$table->string('name');
			$table->string('email');
			$table->string('phone');
			$table->boolean('show_email_on_invoice')->default(false);
			$table->boolean('show_phone_on_invoice')->default(false);
			$table->string('address')->nullable()->default(null);
			$table->text('terms_condition')->nullable()->default(null);
			$table->text('bank_details')->nullable()->default(null);
			$table->string('signature')->nullable()->default(null);
			$table->timestamps();
		});

		$warehouse = new Warehouse();
		$warehouse->name = "Stockifly";
		$warehouse->email = "stockifly@example.com";
		$warehouse->phone = 9999999999;
		$warehouse->terms_condition = "1. Goods once sold will not be taken back or exchanged 
2. All disputes are subject to [ENTER_YOUR_CITY_NAME] jurisdiction only";
		$warehouse->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('warehouses');
	}
}
