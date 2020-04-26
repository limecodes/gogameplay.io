<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;

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
    public function shouldFailIfConnectionNotProvided()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/game/example', []);

        $response->assertStatus(302);
    }

    /**
     *
     * @test
     */
    public function shouldFailIfGameNameIsNumber()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/game/1234', ['connection' => '0']);

        $response->assertStatus(422);
    }

    /**
     *
     * @test
     */
    public function shouldFailIfGameNotExistent()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/game/nonexistent', ['connection' => '0']);

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

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.1']);

        $response->assertStatus(200);
        $response->assertSeeText("The name of the game is ".$game->name." and your ip is 1.1.1.1", $escaped = true);
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

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.2']);
        $response->assertStatus(200);
        $response->assertSeeText("The name of the game is ".$game->name." and your ip is 1.1.1.2", $escaped = true);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.2', 'device' => 'non-mobile', 'country_id' => 1]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorIOSDevice()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.3', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.3', 'device' => 'ios', 'country_id' => 1]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidDevice()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.3', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.3', 'device' => 'android', 'country_id' => 1]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidWifi()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '0'], ['HTTP_GGP_TEST_IP' => '1.1.1.4', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.4', 'device' => 'android', 'mobile_connection' => false, 'country_id' => 1]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorAndroidCellular()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->post('/game/'.$game->name, ['connection' => '1'], ['HTTP_GGP_TEST_IP' => '1.1.1.5', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36']);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.5', 'device' => 'android', 'mobile_connection' => true, 'country_id' => 1]);
    }

    /**
     *
     * @test
     */
    public function shouldRecordVisitorIOSMobileConnection()
    {
        // $this->withoutExceptionHandling();

        // $game = factory(Game::class)->create();

        // $response = $this->post('/game/'.$game->name, ['connection' => 'unknown'], ['HTTP_GGP_TEST_IP' => '1.1.1.6', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1']);
        // $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.6', 'device' => 'ios', 'mobile_connection' => false]);
    }
}
