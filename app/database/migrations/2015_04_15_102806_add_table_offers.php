<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTableOffers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('offers', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->string('area', 100)->nullable();
            $table->string('content', 150)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->boolean('status')->default(false);
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

       Schema::drop('offers');

	}

}
