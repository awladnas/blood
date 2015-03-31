<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStepsToUsersProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_profile', function(Blueprint $table)
		{
            $table->integer('steps');
            $table->integer('total_steps');
            $table->boolean('is_complete')->default(false);
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
            $table->removeColumn('steps');
            $table->removeColumn('total_steps');
            $table->removeColumn('is_complete');
		});
	}

}
