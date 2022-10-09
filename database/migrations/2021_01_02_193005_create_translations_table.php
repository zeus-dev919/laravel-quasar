<?php

use App\Classes\LangTrans;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translations', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('lang_id')->unsigned()->nullable();
			$table->foreign('lang_id')->references('id')->on('langs')->onDelete('cascade')->onUpdate('cascade');
			$table->string('group');
			$table->text('key');
			$table->text('value')->nullable();
			$table->timestamps();
		});

		LangTrans::seedTranslations();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('translations');
	}
}
