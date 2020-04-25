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
     * Should redirect back to main site.
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
     * Should redirect back to main site.
     *
     * @test
     */
    public function shouldFailIfGameNameIsNumber()
    {
        // $this->withoutExceptionHandling();

        $response = $this->get('/game/1234');

        $response->assertStatus(422);
    }

    /**
     * Should redirect back to main site.
     *
     * @test
     */
    public function shouldFailIfGameNotExistent()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/game/nonexistent');

        $response->assertStatus(404);
    }

    /**
     * Should redirect back to main site.
     *
     * @test
     */
    public function shouldShowGamePage()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->get('/game/'.$game->name);

        $response->assertStatus(200);
        $response->assertSeeText("The name of the game is ".$game->name." and your ip is 127.0.0.1 and env is testing", $escaped = true);
    }
}
