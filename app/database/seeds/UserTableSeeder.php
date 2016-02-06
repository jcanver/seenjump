<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        $user = User::create(array(
          'email' => 'tdurden',
          'fname' => 'Tyler',
          'lname' => 'Durden',
          'password' => 'soap',
          'gender' => 'male'
        ));
    

	    $faker = Faker\Factory::create();
	     
	    for ($i = 0; $i < 100000; $i++)
	    {
	      $user = User::create(array(
			'email'   => $faker->unique()->email,
			'fname' => $faker->firstName,
			'lname'  => $faker->lastName,
			'password'   => $faker->word
	      ));
	    }
	}
}