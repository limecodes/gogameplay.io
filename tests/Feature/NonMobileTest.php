<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use App\Models\Country;
use App\External\LocationApi;
use Mockery;

class NonMobileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function shouldLoadAndRecordNonMobileUser()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $this->instance(LocationApi::class, Mockery::mock(LocationApi::class, function($mock) use ($country) {
            $mock->shouldReceive('getCountryOnly')->andReturn([
                'country_id' => $country->id
            ]);
        }));

        $response = $this->get('/nonmobile');

        $this->assertDatabaseHas('visitors', [
            'device' => Config::get('constants.devices.non_mobile'),
            'country_id' => $country->id,
            'mobile_connection' => false,
            'carrier_from_data' => null
        ]);

        $response->assertOk();
    }
}
