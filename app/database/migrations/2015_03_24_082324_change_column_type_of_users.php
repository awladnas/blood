<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeColumnTypeOfUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

       DB::statement('ALTER TABLE `users` MODIFY `valid_until` timestamp  NULL ;');

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement('ALTER TABLE `users` MODIFY `valid_until` datetime;');
	}

}
