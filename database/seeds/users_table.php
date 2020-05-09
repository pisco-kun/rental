<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class users_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			$faker = Faker::create();
			for($i = 1; $i <= 20; $i++){
        DB::table('users')->insert([
					[
							'users_uuid' 	=> $faker->uuid,
							'users_nik' => $faker->unique()->randomNumber,
							'users_name' 	=> $faker->name,
							'users_phone' => $faker->phoneNumber,
							'users_email' => $faker->email,
							'date_created' => date('Y-m-d H:i:s'),
					],	 
				]);
      }
    }
}
