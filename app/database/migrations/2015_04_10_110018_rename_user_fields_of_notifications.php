<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RenameUserFieldsOfNotifications extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement('ALTER TABLE users_notifications CHANGE user_id sender_id INTEGER');
        DB::statement('ALTER TABLE users_notifications CHANGE request_user_id receiver_id INTEGER');
        DB::statement('ALTER TABLE users_notifications CHANGE desc content');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement('ALTER TABLE users_notifications CHANGE sender_id user_id  INTEGER');
        DB::statement('ALTER TABLE users_notifications CHANGE receiver_id request_user_id  INTEGER');
        DB::statement('ALTER TABLE users_notifications CHANGE content desc');
	}

}
