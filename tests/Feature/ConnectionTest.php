<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use App\Models\Visitor;
use App\Models\Country;
use App\Models\MobileNetwork;
use App\External\LocationApi;
use Mockery;

class ConnectionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp():void
    {
        parent::setUp();

        $this->ipAddress = $this->faker->ipv4;
    }

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
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => 'Vodafone'
            ]);
        }));

        $oldIpAddress = $this->faker->ipv4;
        $newIpAddress = $this->ipAddress;

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $oldIpAddress,
            'device' => Config::get('constants.devices.android'),
            'country_id' => null,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device,
        ], ['REMOTE_ADDR' => $newIpAddress]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => $newIpAddress,
            'device' => Config::get('constants.devices.android'),
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

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => null
            ]);
        }));

        $oldIpAddress = $this->faker->ipv4;
        $newIpAddress = $this->ipAddress;

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $oldIpAddress,
            'device' => Config::get('constants.devices.android'),
            'country_id' => null,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device,
        ], ['REMOTE_ADDR' => $newIpAddress]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => $newIpAddress,
            'device' => Config::get('constants.devices.android'),
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

        $oldIpAddress = $this->faker->ipv4;
        $newIpAddress = $this->ipAddress;

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => 'Vodafone'
            ]);
        }));

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $oldIpAddress,
            'device' => Config::get('constants.devices.ios'),
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device
        ], ['REMOTE_ADDR' => $newIpAddress]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => $newIpAddress,
            'device' => Config::get('constants.devices.ios'),
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

        $oldIpAddress = $this->faker->ipv4;
        $newIpAddress = $this->ipAddress;

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => null
            ]);
        }));

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $oldIpAddress,
            'device' => Config::get('constants.devices.ios'),
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device
        ], ['REMOTE_ADDR' => $newIpAddress]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => $newIpAddress,
            'device' => Config::get('constants.devices.ios'),
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

    /**
     *
     * @test
     */
    public function appleUserSaidConnectionChangedToCellularButIpDidNot()
    {
        $country = factory(Country::class)->create();
        $mobileNetworks = factory(MobileNetwork::class, 3)->create(['country_id' => $country->id]);

        $oldIpAddress = $this->faker->ipv4;

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $oldIpAddress,
            'device' => Config::get('constants.devices.ios'),
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response = $this->json('PATCH', '/api/connection/changed', [
            'uid' => $visitor->uid,
            'device' => $visitor->device
        ], ['REMOTE_ADDR' => $oldIpAddress]);

        $this->assertDatabaseHas('visitors', [
            'uid' => $visitor->uid,
            'ip_address' => $oldIpAddress,
            'device' => Config::get('constants.devices.ios'),
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
