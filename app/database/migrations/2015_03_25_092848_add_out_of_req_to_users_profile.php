<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOutOfReqToUsersProfile extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_profile', function(Blueprint $table) {
            $table->boolean('out_of_req')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_profile', function(Blueprint $table) {
            $table->removeColumn('out_of_req');
		});
	}

}
