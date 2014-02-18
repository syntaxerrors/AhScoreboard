<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoundCoopsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('round_coops', function(Blueprint $table) {
			$table->increments('id');
			$table->string('round_id', 10)->index();
			$table->boolean('lossFlag')->index();
			$table->boolean('winFlag')->index();
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
		Schema::drop('round_coops');
	}

}
