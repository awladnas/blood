<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRememberTokenToAdminUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('admin_users', function(Blueprint $table)
        {
            $table->string('remember_token', 100)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('admin_users', function(Blueprint $table)
        {
            $table->removeColumn('remember_token');
        });
	}

}
