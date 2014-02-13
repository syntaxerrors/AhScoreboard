<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideoQuotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('video_quotes', function(Blueprint $table) {
			$table->string('uniqueId', 10)->primary();
			$table->string('video_id', 10)->index();
			$table->string('title');
			$table->string('timeStart');
			$table->string('timeEnd');
			$table->text('quotes');
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
		Schema::drop('video_quotes');
	}

}
