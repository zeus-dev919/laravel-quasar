<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
			$table->string('payment_type', 20)->default('out');
			$table->string('payment_number')->nullable()->default(null);
			$table->date('date');
			$table->double('amount')->default(0);
			$table->double('unused_amount')->default(0);
			$table->double('paid_amount')->default(0);
			$table->bigInteger('payment_mode_id')->unsigned()->nullable();
			$table->foreign('payment_mode_id')->references('id')->on('payment_modes')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			$table->string('payment_receipt')->nullable()->default(null);
			$table->text('notes')->nullable()->default(null);
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
		Schema::dropIfExists('payments');
	}
}
