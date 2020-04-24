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
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    /**
     * Should redirect back to main site.
     *
     * @test
     */
    public function shouldShowGamePage()
    {
        $response = $this->get('/game/example');

        $response->assertStatus(200);
    }
}
