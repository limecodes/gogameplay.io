<?php

use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::Table('games')->insert([
        	'name' => 'example',
            'title' => 'Example',
            'image' => 'http://static.offers.gogameplay.io/images/example.png',
            'price' => 2.79
        ]);
    }
}
