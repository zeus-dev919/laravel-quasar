<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_items', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('order_id')->unsigned();
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('unit_id')->unsigned()->nullable();
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('set null')->onUpdate('cascade');
			$table->float('quantity', 8, 2);

			$table->double('unit_price');
			$table->double('single_unit_price');

			$table->bigInteger('tax_id')->unsigned()->nullable();
			$table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null')->onUpdate('cascade');
			$table->float('tax_rate', 8, 2)->default(0);
			$table->string('tax_type', 10)->nullable()->default(null);

			$table->float('discount_rate', 8, 2)->nullable()->default(null);

			$table->double('total_tax')->nullable()->default(null);
			$table->double('total_discount')->nullable()->default(null);
			$table->double('subtotal');
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
		Schema::dropIfExists('order_items');
	}
}
