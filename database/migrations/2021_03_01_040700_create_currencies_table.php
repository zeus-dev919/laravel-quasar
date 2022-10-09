<?php

use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currencies', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('code');
			$table->string('symbol');
			$table->string('position');
			$table->boolean('is_deletable')->default(true);
			$table->timestamps();
		});

		$newCurrency = new Currency();
		$newCurrency->name = 'Dollar';
		$newCurrency->code = 'USD';
		$newCurrency->symbol = '$';
		$newCurrency->position = 'front';
		$newCurrency->is_deletable = false;
		$newCurrency->save();

		$rupeeCurrency = new Currency();
		$rupeeCurrency->name = 'Rupee';
		$rupeeCurrency->code = 'INR';
		$rupeeCurrency->symbol = '₹';
		$rupeeCurrency->position = 'front';
		$rupeeCurrency->is_deletable = false;
		$rupeeCurrency->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('currencies');
	}
}
