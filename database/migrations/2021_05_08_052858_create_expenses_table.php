<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function (Blueprint $table) {
			$table->id();
			$table->string('bill')->nullable()->default(NULL);
			$table->bigInteger('expense_category_id')->unsigned()->nullable();
			$table->foreign('expense_category_id')->references('id')->on('expense_categories')->onDelete('set null')->onUpdate('cascade');
			$table->bigInteger('warehouse_id')->unsigned()->nullable();
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
			$table->float('amount', 8, 2);
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
			$table->string('notes', 1000)->nullable()->default(null);
			$table->date('date');
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
		Schema::dropIfExists('expenses');
	}
}
