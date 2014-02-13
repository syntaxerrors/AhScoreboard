<?php

class SeedsTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('seeds')->truncate();

        $seeds = array(
            array(
                'name' => 'PreferencesTableSeeder_Update_11_18_2013',
            ),
            array(
                'name' => 'PreferencesTableSeeder_Update_11_20_2013',
            ),
        );

        // Uncomment the below to run the seeder
        DB::table('seeds')->insert($seeds);
    }

}