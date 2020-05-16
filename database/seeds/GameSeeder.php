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
            'slug' => 'example',
            'image' => '/images/games/example.png',
            'price' => 2.79
        ]);
    }
}
