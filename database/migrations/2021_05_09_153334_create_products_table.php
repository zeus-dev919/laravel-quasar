<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('name', 1000);
			$table->string('slug', 1000);
			$table->string('barcode_symbology', 10);
			$table->string('item_code');
			$table->string('image')->nullable()->default(NULL);
			$table->bigInteger('category_id')->unsigned()->nullable();
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('set null')->onUpdate('cascade');
			$table->bigInteger('brand_id')->unsigned()->nullable();
			$table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null')->onUpdate('cascade');
			$table->bigInteger('unit_id')->unsigned()->nullable();
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('set null')->onUpdate('cascade');
			$table->text('description')->nullable()->default(null);
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
			$table->timestamps();
		});

		Schema::create('product_details', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('product_id')->unsigned()->nullable();
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
			$table->float('current_stock', 8, 2)->default(0);
			$table->double('mrp')->nullable()->default(null);
			$table->double('purchase_price');
			$table->double('sales_price');

			$table->bigInteger('tax_id')->unsigned()->nullable();
			$table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null')->onUpdate('cascade');
			$table->string('purchase_tax_type', 10)->nullable()->default('exclusive');
			$table->string('sales_tax_type', 10)->nullable()->default('exclusive');

			$table->integer('stock_quantitiy_alert')->nullable()->default(null);

			$table->integer('opening_stock')->nullable()->default(null);
			$table->date('opening_stock_date')->nullable()->default(null);
			$table->double('wholesale_price')->nullable()->default(null);
			$table->integer('wholesale_quantity')->nullable()->default(null);

			$table->string('status')->default('in_stock'); // In Stock, Out of stock
			$table->timestamps();
		});

		Schema::create('product_custom_fields', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->default(null);
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
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
		Schema::dropIfExists('product_custom_fields');
		Schema::dropIfExists('product_details');
		Schema::dropIfExists('products');
	}
}
