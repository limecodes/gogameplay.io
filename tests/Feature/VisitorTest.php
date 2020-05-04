<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Visitor;
use App\Models\MobileNetwork;
use App\External\LocationApi;
use Mockery;

class VisitorTest extends TestCase
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
    public function shouldFailIfDeviceNotSpecified()
    {
        $response = $this->json('POST', '/api/visitor/set', []);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldFailIfConnectionNotSpecified()
    {   
        $response = $this->json('POST', '/api/visitor/set', ['device' => Config::get('constants.devices.android')]);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitor()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.android'),
            'connection' => false
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabasehas('visitors', ['ip_address' => $this->ipAddress, 'device' => Config::get('constants.devices.android'), 'mobile_connection' => false]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidOnWifi()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.android'),
            'connection' => false
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => $this->ipAddress,
            'device' => Config::get('constants.devices.android'),
            'country_id' => null,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', $this->ipAddress)->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => false,
                'carrier' => false
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidWithConnection()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => 'Vodafone'
            ]);
        }));

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.android'),
            'connection' => true
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => $this->ipAddress,
            'device' => Config::get('constants.devices.android'),
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $visitor = Visitor::where('ip_address', $this->ipAddress)->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => true,
                'carrier' => 'Vodafone'
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidWithConnectionInvalidCarrier()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();
        $mobileNetworks = factory(MobileNetwork::class, 3)->create(['country_id' => $country->id]);

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => null
            ]);
        }));

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.android'),
            'connection' => true
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => $this->ipAddress,
            'device' => Config::get('constants.devices.android'),
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', $this->ipAddress)->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'visitor' => [
                    'uid' => $visitor->uid,
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
    public function shouldRecordVisitorOnAppleOnWifi()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => null
            ]);
        }));

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.ios'),
            'connection' => false
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => $this->ipAddress,
            'device' => Config::get('constants.devices.ios'),
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', $this->ipAddress)->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => false,
                'carrier' => null
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorOnAppleWithConnection()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => 'Vodafone'
            ]);
        }));

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.ios'),
            'connection' => false
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => $this->ipAddress,
            'device' => Config::get('constants.devices.ios'),
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $visitor = Visitor::where('ip_address', $this->ipAddress)->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => true,
                'carrier' => 'Vodafone'
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorOnAppleWithConnectionInvalidCarrier()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryAndDetectCarrier')->andReturn([
                'country_id' => $country->id,
                'carrier' => null
            ]);
        }));

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => Config::get('constants.devices.ios'),
            'connection' => false
        ], ['REMOTE_ADDR' => $this->ipAddress]);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => $this->ipAddress,
            'device' => Config::get('constants.devices.ios'),
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', $this->ipAddress)->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => false,
                'carrier' => null
            ]);
    }
}
