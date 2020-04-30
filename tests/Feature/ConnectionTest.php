<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Visitor;
use App\Models\Country;
use App\Models\MobileNetwork;

class ConnectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function shouldFailIfUidNotProvided()
    {
        $response = $this->json('PATCH', '/api/connection/changed', []);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function connectionChangeAndroidWifiToCellular()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.4',
            'device' => 'android',
            'country_id' => null,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device,
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.5']);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'connection' => true,
                'carrier' => 'Vodafone'
            ]);
    }

    /**
     *
     * @test
     */
    public function connectionChangeAndroidWifiToCellularInvalidCarrier()
    {
        $country = factory(Country::class)->create();
        $mobileNetworks = factory(MobileNetwork::class, 3)->create(['country_id' => $country->id]);

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.4',
            'device' => 'android',
            'country_id' => null,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device,
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.9']);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => '1.1.1.9',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => null
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'visitor' => [
                    'connection' => true,
                    'carrier' => null
                ],
                'carriers_by_country' => [
                    ['name' => $mobileNetworks[0]->name],
                    ['name' => $mobileNetworks[1]->name],
                    ['name' => $mobileNetworks[2]->name]
                ]
            ]);
    }

    /**
     *
     * @test
     */
    public function connectionChangeAppleWifiToCellular()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.4',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.5']);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'connection' => true,
                'carrier' => 'Vodafone'
            ]);
    }

    /**
     *
     * @test
     */
    public function connectionChangedAppleWifiToCellularInvalidCarrier()
    {
        $country = factory(Country::class)->create();
        $mobileNetworks = factory(MobileNetwork::class, 3)->create(['country_id' => $country->id]);

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.4',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.9']);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => '1.1.1.9',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'visitor' => [
                    'connection' => false,
                    'carrier' => null
                ],
                'carriers_by_country' => [
                    ['name' => $mobileNetworks[0]->name],
                    ['name' => $mobileNetworks[1]->name],
                    ['name' => $mobileNetworks[2]->name]
                ]
            ]);
    }
}
