<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->string('unique_id', 20); // Used for invoice download
			$table->string('invoice_number', 20);
			$table->string('invoice_type', 20)->nullable()->default(null);
			$table->string('order_type', 20)->default('sales');
			$table->dateTime('order_date');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('user_id')->unsigned()->nullable()->default(null);
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('tax_id')->unsigned()->nullable();
			$table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade')->onUpdate('cascade');
			$table->float('tax_rate', 8, 2)->nullable()->default(null);

			$table->double('tax_amount')->default(0);
			$table->double('discount')->nullable()->default(null);
			$table->double('shipping')->nullable()->default(null);
			$table->double('subtotal');
			$table->double('total');
			$table->double('paid_amount')->default(0);
			$table->double('due_amount')->default(0);

			$table->string('order_status', 20);
			$table->text('notes')->nullable()->default(null);
			$table->string('document')->nullable()->default(null);
			$table->bigInteger('staff_user_id')->unsigned()->nullable();
			$table->foreign('staff_user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
			$table->string('payment_status', 20)->default('unpaid');
			$table->float('total_items', 8, 2)->default(0);
			$table->float('total_quantity', 8, 2)->default(0);

			$table->text('terms_condition')->nullable()->default(null);

			// $table->float('profit', 8, 2)->default(0);
			$table->boolean('is_deletable')->default(true);
			$table->boolean('cancelled')->default(false);
			$table->bigInteger('cancelled_by')->unsigned()->nullable();
			$table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
			$table->timestamps();
		});

		Schema::create('order_custom_fields', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('order_id')->unsigned();
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
			$table->string('field_name');
			$table->string('field_value')->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('order_custom_fields');
		Schema::dropIfExists('orders');
	}
}
