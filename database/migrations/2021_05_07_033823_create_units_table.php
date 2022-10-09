<?php

use App\Classes\Common;
use App\Models\Unit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('units', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('short_name');
			$table->string('base_unit')->nullable()->default(null);
			$table->bigInteger('parent_id')->unsigned()->nullable()->default(null);
			$table->foreign('parent_id')->references('id')->on('units')->onDelete('cascade')->onUpdate('cascade');
			$table->string('operator');
			$table->string('operator_value');
			$table->boolean('is_deletable')->default(true);
			$table->timestamps();
		});

		$allUnits = Common::allUnits();

		foreach ($allUnits as $allUnit) {
			$unit = new Unit();
			$unit->name = $allUnit['name'];
			$unit->short_name = $allUnit['short_name'];
			$unit->operator = $allUnit['operator'];
			$unit->operator_value = $allUnit['operator_value'];
			$unit->is_deletable = false;
			$unit->save();
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('units');
	}
}
