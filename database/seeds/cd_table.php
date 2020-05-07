<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class cd_table extends Seeder
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
        DB::table('cd')->insert([
					[
							'cd_uuid' 	=> $faker->uuid,
							'cd_title' => $faker->word,
							'cd_rate' 	=> $faker->randomElement($array = array ('10000','12000','15000','18000', '20000')),
							'cd_category' 	=> $faker->randomElement($array = array ('1','2','3')),
							'cd_quantity' 	=> $faker->randomDigitNot(0),
							'created_by' 		=> $faker->randomElement($array = array ('1','2')),
							'date_created' 		=> date('Y-m-d H:i:s'),
					],
				]);
      }
    }
}