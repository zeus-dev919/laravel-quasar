<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseHistoryTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('warehouse_history', function (Blueprint $table) {
			$table->id();
			$table->date('date');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('user_id')->unsigned()->nullable()->default(null);
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('order_id')->unsigned()->nullable()->default(null);
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('order_item_id')->unsigned()->nullable()->default(null);
			$table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('product_id')->unsigned()->nullable()->default(null);
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('payment_id')->unsigned()->nullable()->default(null);
			$table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('expense_id')->unsigned()->nullable()->default(null);
			$table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade')->onUpdate('cascade');
			$table->double('amount')->default(0);
			$table->float('quantity', 8, 2)->default(0);
			$table->string('status')->nullable()->default(null);
			$table->string('type')->nullable()->default(null);
			$table->string('transaction_number')->nullable()->default(null);
			$table->dateTime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('warehouse_history');
	}
}
