<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class cd_category_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			$faker = Faker::create();
			$x = array ('Action','Comedy','Fantasy','Drama', 'Romantic');
			for($i = 0; $i < 5; $i++){
        DB::table('cd_category')->insert([
					[
							'cd_category_uuid' 	=> $faker->uuid,
							'cd_category_name' => $x[$i],
							'created_by' 		=> $faker->randomElement($array = array ('1','2')),
							'date_created' 		=> date('Y-m-d H:i:s'),
					],
				]);
      }
    }
}