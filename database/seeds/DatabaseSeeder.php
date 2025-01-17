<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountrySeeder::class);
        $this->call(MobileNetworkSeeder::class);
        $this->call(GameSeeder::class);
        $this->call(OfferSeeder::class);
    }
}
