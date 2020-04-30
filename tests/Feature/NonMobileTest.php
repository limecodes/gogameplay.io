<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Country;

class NonMobileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function nonMobilePageShouldLoad()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $response = $this->get('/nonmobile', ['HTTP_GGP_TEST_IP' => '1.1.1.1']);

        $response->assertOk();
    }

    /**
     *
     * @test
     */
    public function shouldRecordNonMobileUser()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $response = $this->get('/nonmobile', ['HTTP_GGP_TEST_IP' => '1.1.1.1']);

        $this->assertDatabaseHas('visitors', [
            'ip_address' => '1.1.1.1',
            'device' => 'non-mobile',
            'country_id' => $country->id,
            'mobile_connection' => null,
            'carrier_from_data' => null
        ]);

        $response->assertOk();
    }
}
