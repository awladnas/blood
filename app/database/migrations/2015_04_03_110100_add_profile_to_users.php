<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProfileToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
            $table->string('blood_group', 5)->nullable();
            $table->string('name', 60)->nullable();
            $table->string('zone', 100)->nullable();
            $table->string('city', 60)->nullable();
            $table->string('country', 60)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('steps')->nullable();
            $table->integer('total_steps')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->string('post_code', 15)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
            $table->removeColumn('blood_group');
            $table->removeColumn('name');
            $table->removeColumn('zone');
            $table->removeColumn('city');
            $table->removeColumn('country');
            $table->removeColumn('latitude');
            $table->removeColumn('longitude');
            $table->removeColumn('steps');
            $table->removeColumn('total_steps');
            $table->removeColumn('is_complete');
            $table->removeColumn('post_code');
		});
	}

}
