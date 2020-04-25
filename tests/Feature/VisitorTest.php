<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;

class VisitorTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Should redirect back to main site.
     *
     * @test
     */
    public function visitorDataShouldBeRecorded()
    {
        // $this->withoutExceptionHandling();

        // $game = factory(Game::class)->create();

        // $response = $this->get('/game/'.$game->name);

        // $this->assertDatabaseHas('visitors', );
    }

    /**
     *
     * @test
     */
    public function visitorIpAddressShouldUpdate()
    {
        // $response = $this->patch('/api/visitor', [
        //     'uuid' => $uuid,
        //     'ip_address' => $newIpAddress
        // ]);

        // $this->assertDatabaseHas('visitors') // Has new ip address
    }
}
