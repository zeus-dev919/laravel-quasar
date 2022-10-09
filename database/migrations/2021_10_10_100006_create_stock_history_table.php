<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHistoryTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_history', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('warehouse_id')->unsigned();
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			$table->float('quantity', 8, 2);
			$table->float('old_quantity', 8, 2)->default(0);
			$table->string('order_type', 20)->nullable()->default('sales');
			$table->string('stock_type', 20)->default('in');
			$table->string('action_type', 20)->default('add'); // [add, edit, delete]
			$table->bigInteger('created_by')->unsigned()->nullable();
			$table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('stock_history');
	}
}
