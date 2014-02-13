<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNamesToActorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('actors', function(Blueprint $table) {
			$table->renameColumn('name', 'firstName');
			$table->string('lastName')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('actors', function(Blueprint $table) {
			$table->renameColumn('firstName', 'name');
			$table->dropColumn('lastName');
		});
	}

}
