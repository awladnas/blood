<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('offers_contacts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('contact');
            $table->integer('contactable_id')->unsigned();
            $table->string('contactable_type');
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
		Schema::drop('offers_contacts');
	}

}
