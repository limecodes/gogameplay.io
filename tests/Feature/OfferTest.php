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

    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryMatchDeviceAndroidMatchCarrier()
    {
        $this->withoutExceptionHandling();

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
    public function shouldReturnSingleOfferMatchCountryMatchDeviceAppleMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
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
    public function shouldReturnNullOfferMatchCountryNonMatchDeviceAndroidMatchCarrier()
    {
        $this->withoutExceptionHandling();

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
    public function shouldReturnNullOfferMatchCountryNonMatchDeviceAppleMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
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
                'success' => false,
                'offer' => null
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryMatchDeviceAndroidMatchAnyCarrier()
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
    public function shouldReturnSingleOfferMatchCountryMatchDeviceAppleMatchAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $country->id,
            'device' => 'ios',
            'carrier' => '*',
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
    public function shouldReturnNullOfferMatchCountryNonMatchDeviceAndroidAnyCarrier()
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
            'carrier' => '*',
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
    public function shouldReturnNullOfferMatchCountryNonMatchDeviceAppleAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $country->id,
            'device' => 'android',
            'carrier' => '*',
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

    // TODO: AnyDeviceSpecificCarrier

    /**
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryAnyDeviceAndroidAnyCarrier()
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
            'device' => '*',
            'carrier' => '*',
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
    public function shouldReturnSingleOfferMatchCountryAnyDeviceAppleAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $country->id,
            'device' => '*',
            'carrier' => '*',
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
    public function shouldReturnNullMismatchCountryAnyDeviceAndroidAnyCarrier()
    {
        $this->withoutExceptionHandling();

        $visitorCountry = factory(Country::class)->create();
        $offerCountry = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'android',
            'country_id' => $visitorCountry->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $offerCountry->id,
            'device' => '*',
            'carrier' =>  '*',
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
    public function shouldReturnNullMismatchCountryAnyDeviceAppleAnyCarrier()
    {
        $visitorCountry = factory(Country::class)->create();
        $offerCountry = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $visitorCountry->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $offerCountry->id,
            'device' => '*',
            'carrier' =>  '*',
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
    public function shouldReturnSingleOfferAnyCountryAnyDeviceAndroidAnyCarrier()
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
            'country_id' => null,
            'device' => '*',
            'carrier' => '*',
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
    public function shouldReturnSingleOfferAnyCountryAnyDeviceAppleAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => '1.1.1.5',
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => null,
            'device' => '*',
            'carrier' => '*',
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
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryAnyDeviceAndroidMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $country->id,
            'device' => '*',
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
     *
     * @test
     */
    public function shouldReturnSingleOfferMatchCountryAnyDeviceAppleMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class)->create([
            'country_id' => $country->id,
            'device' => '*',
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
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryMatchDeviceAndroidMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'country_id' => $country->id,
            'device' => 'android',
            'carrier' => 'Vodafone',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryMatchDeviceAppleMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'country_id' => $country->id,
            'device' => 'ios',
            'carrier' => 'Vodafone',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryMatchDeviceAndroidAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl($width = 640, $height = 480),
            'country_id' => $country->id,
            'device' => 'android',
            'carrier' => '*',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryMatchDeviceAppleAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl(),
            'country_id' => $country->id,
            'device' => 'ios',
            'carrier' => '*',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryAnyDeviceAndroidMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl(),
            'country_id' => $country->id,
            'device' => '*',
            'carrier' => 'Vodafone',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryAnyDeviceAppleMatchCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl(),
            'country_id' => $country->id,
            'device' => '*',
            'carrier' => 'Vodafone',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryAnyDeviceAndroidAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl(),
            'country_id' => $country->id,
            'device' => '*',
            'carrier' => '*',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersMatchCountryAnyDeviceAppleAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'ios',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl(),
            'country_id' => $country->id,
            'device' => '*',
            'carrier' => '*',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    /**
     *
     *
     * @test
     */
    public function shouldReturnMultipleOffersAnyCountryAnyDeviceAndroidAnyCarrier()
    {
        $country = factory(Country::class)->create();

        $visitor = factory(Visitor::class)->create([
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'Vodafone'
        ]);

        $offer = factory(Offer::class, 3)->create([
            'text' => $this->faker->words(5, true),
            'img' => $this->faker->imageUrl(),
            'country_id' => null,
            'device' => '*',
            'carrier' => '*',
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
                    ['text' => $offer[0]->text, 'img' => $offer[0]->img, 'url' => $offer[0]->url],
                    ['text' => $offer[1]->text, 'img' => $offer[1]->img, 'url' => $offer[1]->url],
                    ['text' => $offer[2]->text, 'img' => $offer[2]->img, 'url' => $offer[2]->url]
                ]
            ]);
    }

    // 
    // 
    // 
    // 
    // 

    // shouldReturnNullMatchCountryMatchDeviceMismatchCarrier
    // shouldReturnNullMatchCountryMismatchDeviceMatchCarrier

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
            'text' => $this->faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'country_id' => $country->id,
            'device' => '*',
            'carrier' => 'A-Mobile',
            'type' => 2
        ]);

        $dd = $visitor->country->offers()
            ->whereIn('device', [$visitor->device, '*'])
            ->whereIn('carrier', [$visitor->carrier_from_data, '*'])
            ->where('type', 'backup')->get();

        // dd($dd);

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
            'ip_address' => $this->faker->ipv4,
            'device' => 'android',
            'country_id' => $country->id,
            'mobile_connection' => true,
            'carrier_from_data' => 'A-Mobile'
        ]);

        $carrierOffers = factory(Offer::class, 2)->create([
            'text' => $this->faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'device' => 'android',
            'country_id' => $country->id,
            'carrier' => 'A-Mobile',
            'type' => 2
        ]);

        $anyCarrierOffers = factory(Offer::class, 2)->create([
            'text' => $this->faker->words(5, true),
            'img' => 'https://via.placeholder.com/350',
            'device' => 'android',
            'country_id' => $country->id,
            'carrier' => '*',
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
