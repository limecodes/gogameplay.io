<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicOperationTest extends TestCase
{
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

        $response = $this->get('/game/example');

        $response->assertStatus(200);
    }
}
