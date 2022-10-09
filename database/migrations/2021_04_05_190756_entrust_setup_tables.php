<?php

use App\Classes\PermsSeed;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EntrustSetupTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return  void
	 */
	public function up()
	{
		// Create table for storing roles
		Schema::create('roles', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('display_name')->nullable();
			$table->string('description')->nullable();
			$table->timestamps();
		});

		// Create table for associating roles to users (Many-to-Many)
		Schema::create('role_user', function (Blueprint $table) {
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('role_id')->unsigned();

			$table->foreign('user_id')->references('id')->on('users')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('role_id')->references('id')->on('roles')
				->onUpdate('cascade')->onDelete('cascade');

			$table->primary(['user_id', 'role_id']);
		});

		// Create table for storing permissions
		Schema::create('permissions', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('display_name')->nullable();
			$table->string('description')->nullable();
			$table->string('module_name')->nullable()->default(null);
			$table->timestamps();
		});

		// Create table for associating permissions to roles (Many-to-Many)
		Schema::create('permission_role', function (Blueprint $table) {
			$table->bigInteger('permission_id')->unsigned();
			$table->bigInteger('role_id')->unsigned();

			$table->foreign('permission_id')->references('id')->on('permissions')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('role_id')->references('id')->on('roles')
				->onUpdate('cascade')->onDelete('cascade');

			$table->primary(['permission_id', 'role_id']);
		});

		// Seeding Data
		$adminRole = new Role();
		$adminRole->name = 'admin';
		$adminRole->display_name = 'Admin';
		$adminRole->description = 'Admin is allowed to manage everything of the app.';
		$adminRole->save();

		$warehouse = Warehouse::first();
		$admin = User::where('name', 'Admin')->first();
		$admin->warehouse_id = $warehouse->id;
		$admin->role_id = $adminRole->id;
		$admin->save();
		$admin->roles()->attach($adminRole->id);

		$adminDetails = new UserDetails();
		$adminDetails->warehouse_id = $warehouse->id;
		$adminDetails->user_id = $admin->id;
		$adminDetails->save();

		// Seeding Permissions
		PermsSeed::seedPermissions();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return  void
	 */
	public function down()
	{
		Schema::drop('permission_role');
		Schema::drop('permissions');
		Schema::drop('role_user');
		Schema::drop('roles');
	}
}
