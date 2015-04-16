<?php

class Admin_usersTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('admin_users')->truncate();

        $arrData = [
            'name' => 'admin',
            'email' => 'admin@lifeli.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin'
        ];

		// Uncomment the below to run the seeder
		 DB::table('admin_users')->insert($arrData);
	}

}
