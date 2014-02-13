<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('actors', function(Blueprint $table) {
			$table->string('uniqueId', 10)->primary();
			$table->string('user_id', 10)->nullable()->index();
			$table->string('name');
			$table->string('keyName')->unique();
			$table->text('bio')->nullable();
			$table->string('twitterHandle')->nullable();
			$table->string('rtLink')->nullable();
			$table->string('rtWikiLink')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('members');
	}

}
