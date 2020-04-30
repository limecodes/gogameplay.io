<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Visitor;
use App\Models\Country;
use App\Models\Offer;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function shouldFailIfUIDIsNotSpecified()
    {
        $response = $this->json('POST', '/api/offers/fetch', []);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferIfCriteriaMatches()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $country->id,
            'carrier' => 'Vodafone',
            'type' => 1
        ]);

        $response = $this->json('POST', '/api/offers/fetch', [
            'uid' => $visitor->uid
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'url' => $offer->url
            ]);
    }

    // TODO: Write case where no main offer exists but backup exists
    // TODO: Write case for non-mobile offers
    // TODO: Write case if offer for country exists in db but doesn't match carrier
    // TODO: Same as ^ but with backup offers
}
