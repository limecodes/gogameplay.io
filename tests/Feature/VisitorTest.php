<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Visitor;
use App\Models\MobileNetwork;

class VisitorTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function shouldFailIfDeviceNotSpecified()
    {

        $response = $this->json('POST', '/api/visitor/set', [], ['HTTP_GGP_TEST_IP' => '1.1.1.1']);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldFailIfConnectionNotSpecified()
    {
        $response = $this->json('POST', '/api/visitor/set', ['device' => 'android'], ['HTTP_GGP_TEST_IP' => '1.1.1.1']);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidOnWifi()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'android',
            'connection' => false
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.4']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.4',
            'device' => 'android',
            'country_id' => null,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.4')->first();

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

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'android',
            'connection' => true
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.5']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.5')->first();

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

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'android',
            'connection' => true
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.9']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.9',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.9')->first();

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

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'ios',
            'connection' => false
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.4']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.4',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.4')->first();

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

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'ios',
            'connection' => false
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.5']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.5')->first();

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

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'ios',
            'connection' => false
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.9']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.9',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.9')->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => false,
                'carrier' => null
            ]);
    }
}
