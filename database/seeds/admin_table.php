<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class admin_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			$faker = Faker::create();
        DB::table('admin')->insert([
					[
							'admin_name' 	=> 'demo',
							'admin_email' => 'demo@mail.com',
							'admin_uuid' 	=> $faker->uuid,
							'admin_password' 		=> '$2y$10$aaPl4s/QqDFpF7lT1zWbVecaCME7t2te6W1WwoIuaXkBgKPPPGw.e',
					],
					[
							'admin_name' 	=> 'demo2',
							'admin_email' => 'demo2@mail.com',
							'admin_uuid' 	=> $faker->uuid,
							'admin_password' 		=> '$2y$10$aaPl4s/QqDFpF7lT1zWbVecaCME7t2te6W1WwoIuaXkBgKPPPGw.e',
					],				 
				]);
    }
}
