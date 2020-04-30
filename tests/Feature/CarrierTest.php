<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Visitor;
use App\Models\Country;

class CarrierTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function shouldFailIfUUIDNotSpecified()
    {
        $response = $this->json('PATCH', '/api/carrier/update', []);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldFailIfCarrierNotSpecified()
    {
        $response = $this->json('PATCH', '/api/carrier/update', [
            'uid' => (string) Str::uuid()
        ]);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldUpdateCarrierAndroid()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.9',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/carrier/update', [
            'uid' => $visitor->uid,
            'carrier' => 'A-Mobile'
        ]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'connection' => true,
                'carrier' => 'A-Mobile'
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldUpdateCarrierApple()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.9',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/carrier/update', [
            'uid' => $visitor->uid,
            'carrier' => 'A-Mobile'
        ]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => '1.1.1.9',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'connection' => false,
                'carrier' => 'A-Mobile'
            ]);
    }
}
