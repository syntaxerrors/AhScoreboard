<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddParentIdToVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('videos', function(Blueprint $table) {
			$table->string('parentId', 10)->nullable()->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('videos', function(Blueprint $table) {
			$table->dropColumn('parentId');
		});
	}

}
