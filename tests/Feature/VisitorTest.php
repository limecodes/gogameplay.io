<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Country;

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
    public function shouldRecordVisitorAndroid()
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

        $country = factory(Country::model)->create();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'android',
            'connection' => true
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.5']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
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

        $country = factory(Country::model)->create();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'android',
            'connection' => true
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.9']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.9',
            'device' => 'android',
            'country_id' => $country->id,
            'carrier_from_data' => null
        ]);

        $visitor = Visitor::where('ip_address', '1.1.1.9')->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'uid' => $visitor->uid,
                'connection' => true,
                'carrier' => null
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorOnApple()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::model)->create();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'ios',
            'connection' => false
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.4']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.4',
            'device' => 'ios',
            'country_id' => $country->id,
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

        $country = factory(Country::model)->create();

        $response = $this->json('POST', '/api/visitor/set', [
            'device' => 'ios',
            'connection' => false
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.5']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
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
}
