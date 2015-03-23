<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLatitudeAndLongitudeToProfiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_profile', function(Blueprint $table)
		{
			$table->string('latitude')->nullable();
			$table->string('longitude')->longitude();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_profile', function(Blueprint $table)
		{
			$table->removeColumn('latitude');
			$table->removeColumn('longitude');
		});
	}

}
