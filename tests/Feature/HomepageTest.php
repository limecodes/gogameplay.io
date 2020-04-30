<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    /**
     *
     * @test
     */
    public function homepageShouldBeOK()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
