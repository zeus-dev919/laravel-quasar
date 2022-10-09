<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
			$table->bigInteger('role_id')->unsigned()->nullable()->default(null);
			$table->string('user_type')->default('customers');
			$table->boolean('login_enabled')->default(true);
			$table->string('name');
			$table->string('email');
			$table->string('password')->nullable();
			$table->string('phone')->nullable()->default(null);
			$table->string('profile_image')->nullable()->default(null);
			$table->string('address', 1000)->nullable()->default(null);
			$table->string('shipping_address', 1000)->nullable()->default(null);
			$table->string('email_verification_code', 50)->nullable()->default(null);

			$table->string('status')->default('enabled');
			$table->string('reset_code')->nullable()->default(null);

			$table->string('timezone', 50)->default('Asia/Kolkata');
			$table->string('date_format', 20)->default('d-m-Y');
			$table->string('date_picker_format', 20)->default('dd-mm-yyyy');
			$table->string('time_format', 20)->default('h:i a');

			$table->timestamps();
		});

		Schema::create('user_details', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
			$table->bigInteger('user_id')->unsigned()->nullable()->default(null);
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

			$table->double('opening_balance')->default(0);
			$table->string('opening_balance_type', 20)->default('receive');
			$table->integer('credit_period')->default(0);
			$table->double('credit_limit')->default(0);

			$table->integer('purchase_order_count')->default(0);
			$table->integer('purchase_return_count')->default(0);
			$table->integer('sales_order_count')->default(0);
			$table->integer('sales_return_count')->default(0);

			$table->double('total_amount')->default(0);
			$table->double('paid_amount')->default(0);
			$table->double('due_amount')->default(0);

			$table->timestamps();
		});

		User::create([
			'name' => 'Admin',
			'email' => 'admin@example.com',
			'password' => '12345678',
			'user_type' => 'staff_members'
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_details');
		Schema::dropIfExists('users');
	}
}
