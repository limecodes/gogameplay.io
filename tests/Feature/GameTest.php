<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        $this->androidUserAgent = 'Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-A105F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.1 Chrome/75.0.3770.143 Mobile Safari/537.36';
        $this->appleUserAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1';
    }

    /**
     *
     * @test
     */
    public function nonMobileUserShouldBeRedirected()
    {
        // For some reason it's not going through the middleware so a game has to exist
        // Makes sense because the homepage would display an existing game

        $game = factory(Game::class)->create();

        $response = $this->get('/game/'.$game->slug);

        $response->assertRedirect('/nonmobile');
    }

    /**
     *
     * @test
     */
    public function shouldFailIfGameNotExistent()
    {
        $response = $this->get('/game/nonexistent', ['HTTP_USER_AGENT' => $this->androidUserAgent]);

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

        $response = $this->get('/game/'.$game->slug, ['HTTP_USER_AGENT' => $this->androidUserAgent]);

        $response
            ->assertStatus(200)
            ->assertViewHas('<h4>'.$game->name.'</h4>', false);
    }
}
