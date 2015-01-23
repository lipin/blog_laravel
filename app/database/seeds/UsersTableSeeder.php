<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

	
			User::create([
				'email'    => 'admin@admin.com',
	            'password' => Hash::make('password'),
	            'nickname' => 'admin',
	            'is_admin' => 1,

			]);
	}

}