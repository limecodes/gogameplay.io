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
    use RefreshDatabase, WithFaker;

    /**
     *
     * @test
     */
    public function shouldFailIfUIDIsNotSpecified()
    {
        $response = $this->json('POST', '/api/offers/fetch', []);

        $response->assertStatus(422);
    }

    // First I'm going to have offers that target a specific country and specific carrier
    // For Everything else, I'm going to have a redirect
    // Later, I'm going to have backup offers
    // Also, if user hits "back button" I need offers for an offer wall



    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryMatchDeviceMatchCarrier()
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
            'device' => 'android',
            'carrier' => 'Vodafone',
            'type' => 1
        ]);

        $response = $this->json('POST', '/api/offers/fetch', [
            'uid' => $visitor->uid
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'offer' => [
                    'url' => $offer->url
                ]
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryNonMatchDeviceMatchCarrier()
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
            'device' => 'ios',
            'carrier' => 'Vodafone',
            'type' => 1
        ]);

        $response = $this->json('POST', '/api/offers/fetch', [
            'uid' => $visitor->uid
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => false,
                'offer' => null
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryMatchDeviceMatchAnyCarrier()
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
            'device' => 'android',
            'carrier' => '*',
            'type' => 2
        ]);
    }

    // shouldReturnSingleOfferMatchCountryMatchDeviceMatchAnyCarrier
    // shouldReturnSingleOfferMatchCountryAnyDeviceAnyCarrier
    // shouldReturnSingleOfferAnyCountryAnyDeviceAnyCarrier
    // shouldReturnMultipleOffersMatchCountryMatchDeviceMatchCarrier
    // shouldReturnMultipleOffersMatchCountryMatchDeviceAnyCarrier
    // shouldReturnMultipleOffersMatchCountryAnyDeviceMatchCarrier
    // shouldReturnMultipleOffersMatchCountryAnyDeviceAnyCarrier
    // shouldReturnMultipleOffersAnyCountryAnyDeviceAnyCarrier

    /**
     *
     * @test
     */
    public function offerForCountryExistsButCarrierMismatchNoBackups()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
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
                'success' => false,
                'offer' => null
            ]);
    }

    /**
     *
     * @test
     */
    public function offerForCountryExistsButCarrierMistachHasBackupsForCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $offers = factory(Offer::class, 3)->create([
            'text' => $faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'country_id' => $country->id,
            'carrier' => 'A-Mobile',
            'type' => 2
        ]);

        $response = $this->json('POST', '/api/offers/fetch', [
            'uid' => $visitor->uid
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => false,
                'offer' => [
                    ['text' => $offers[0]->text, 'img' => $offers[0]->img, 'url' => $offers[0]->url],
                    ['text' => $offers[1]->text, 'img' => $offers[1]->img, 'url' => $offers[1]->url],
                    ['text' => $offers[2]->text, 'img' => $offers[2]->img, 'url' => $offers[2]->url]
                ]
            ]);
    }

    /**
     *
     * @test
     */
    public function offerForCountryExistsBackupsForCarrierAndCountry()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $carrierOffers = factory(Offer::class, 2)->create([
            'text' => $faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'country_id' => $country->id,
            'carrier' => 'A-Mobile',
            'type' => 2
        ]);

        $anyCarrierOffers = factory(Offer::class, 2)->create([
            'text' => $faker->words(5, true),
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => '*'
        ]);

        $response = $this->json('POST', '/api/offers/fetch', [
            'uid' => $visitor->uid
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => false,
                'offer' => [
                    ['text' => $carrierOffers[0]->text, 'img' => $carrierOffers[0]->img, 'url' => $carrierOffers[0]->url],
                    ['text' => $carrierOffers[1]->text, 'img' => $carrierOffers[1]->img, 'url' => $carrierOffers[1]->url],
                    ['text' => $anyCarrierOffers[0]->text, 'img' => $anyCarrierOffers[0]->img, 'url' => $anyCarrierOffers[0]->url],
                    ['text' => $anyCarrierOffers[1]->text, 'img' => $anyCarrierOffers[1]->img, 'url' => $anyCarrierOffers[1]->url]
                ]
            ]);
    }

    /**
     *
     * @test
     */
    public function offerForCountryExistButCarrierMismatchNoCarrierBackups()
    {

    }

    /**
     *
     * @test
     */
    public function offerForCountryDoesNotExist()
    {

    }

    /**
     *
     * @test
     */
    public function nonMobileOfferForCountryDoesNotExist()
    {

    }

    /**
     *
     * @test
     */
    public function nonMobileOfferForCountryExists()
    {

    }

    // TODO: Write case where no main offer exists but backup exists
    // TODO: Write case for non-mobile offers
    // TODO: Write case if offer for country exists in db but doesn't match carrier
    // TODO: Same as ^ but with backup offers
}
