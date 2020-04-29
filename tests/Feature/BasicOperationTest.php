<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;
use App\Models\Country;
use App\Models\Visitor;
use App\Models\MobileNetwork;

class BasicOperationTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function hittingTheRootShouldRedirect()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/');

        $response->assertRedirect(env('APP_REDIRECT_HOME'));
    }

    /**
     *
     * @test
     */
    public function nonMobileUserShouldBeRedirected()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/game/example', ['connection' => '0']);

        $response->assertRedirect('/nonmobile');
    }

    /**
     *
     * @test
     */
    public function shouldFailIfConnectionNotProvided()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/game/example', []);

        $response
            ->assertStatus(302)
            ->assertRedirect(env('/nonmobile'));
    }

    /**
     *
     * @test
     */
    public function shouldFailIfGameNameIsNumber()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/game/1234', ['connection' => '0'], ['HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldFailIfGameNotExistent()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/game/nonexistent', ['connection' => '0'], ['HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);

        $response->assertStatus(404);
    }

    /**
     *
     * @test
     */
    public function shouldShowGamePage()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.1']);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorNonMobileDevice()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/nonmobile', ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.2']);
        $response->assertStatus(200);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.2', 'device' => 'non-mobile', 'country_id' => $country->id]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorIOSDevice()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.3', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.3', 'device' => 'ios']);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidDevice()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.3', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.3', 'device' => 'android']);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidWifi()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.4', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.4', 'device' => 'android', 'mobile_connection' => false, 'carrier_from_data' => null]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidCellular()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '1'], ['HTTP_GGP_TEST_IP' => '1.1.1.5', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.5', 'device' => 'android', 'mobile_connection' => true, 'carrier_from_data' => 'Vodafone']);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorIOSMobileConnection()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.6', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.6', 'device' => 'ios', 'mobile_connection' => true, 'carrier_from_data' => 'Vodafone']);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorIOSWifiConnection()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.7', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.7', 'device' => 'ios', 'mobile_connection' => false, 'carrier_from_data' => null]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorCountry()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.8', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.8', 'device' => 'ios', 'mobile_connection' => false, 'country_id' => $country->id]);
    }

    /**
     *
     * @test
     */
    public function whenConnectionChangedShouldReFetch()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.4', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);

        $visitor = Visitor::where('ip_address', '1.1.1.4')->first();

        $response = $this->post('/api/connectionchanged', ['uid' => $visitor->uid], ['HTTP_GGP_TEST_IP' => '1.1.1.5', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['uid' => $visitor->uid, 'ip_address' => '1.1.1.5', 'mobile_connection' => true, 'carrier_from_data' => 'Vodafone']);
    }

    /**
     *
     * @test
     */
    public function whenConnectionChangedButCarrierUnknown()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.7', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);

        $visitor = Visitor::where('ip_address', '1.1.1.7')->first();

        $response = $this->post('/api/connectionchanged', ['uid' => $visitor->uid], ['HTTP_GGP_TEST_IP' => '1.1.1.9', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['uid' => $visitor->uid, 'ip_address' => '1.1.1.9', 'mobile_connection' => false, 'carrier_from_data' => 'unknown']);
        $response
            ->assertJsonStructure([
                'visitor', 'carriers_by_country'
            ])
            ->assertJsonPath('visitor.carrier', false);
    }

    /**
     *
     * @test
     */
    public function shouldUpdateVisitorCarrierAndReturnCarrier()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $country = factory(Country::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.9', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);

        $visitor = Visitor::where('ip_address', '1.1.1.9')->first();
        $response = $this->post('/api/updatecarrier', ['uid' => $visitor->uid, 'carrier' => 'A-Mobile'], ['HTTP_GGP_TEST_IP' => '1.1.1.9', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['uid' => $visitor->uid, 'ip_address' => '1.1.1.9', 'mobile_connection' => false, 'carrier_from_data' => 'A-Mobile']);
        $response
            ->assertOk()
            ->assertJson([
                'connection' => false,
                'carrier' => 'A-Mobile'
            ]);
    }

    /**
     *
     * @test
     */
    public function shouldNotLookupDatabaseIfDataExists()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $visitor = factory(Visitor::class)->create();
        $visitor->country_id = 2;
        $visitor->save();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => $visitor->ip_address, 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $response->assertOk();
        $this->assertDatabaseHas('visitors', ['uid' => $visitor->uid, 'ip_address' => $visitor->ip_address, 'mobile_connection' => true, 'country_id' => 2]);
    }

    /**
     *
     * @test
     */
    public function shouldReturnCarrierListIfMobileConnectionButCarrierInvalid()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();
        $visitor = factory(Visitor::class)->create(['country_id' => $country->id]);

        $mobileNetworks = factory(MobileNetwork::class, 3)->create(['country_id' => $country->id]);

        $response = $this->post('/api/carrierlist', ['uid' => $visitor->uid]);
        $response
            ->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                ['name' => $mobileNetworks[0]->name],
                ['name' => $mobileNetworks[1]->name],
                ['name' => $mobileNetworks[2]->name]   
            ]);
    }

    /**
     *
     * @test
     */
    public function returningUserChangedIPShouldFetchSameUUID()
    {

    }

    /**
     *
     * @test
     */
    public function existingIpDifferentDeviceShouldCreateNewRecord()
    {
        
    }
}
