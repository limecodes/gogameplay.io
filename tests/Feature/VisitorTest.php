<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VisitorTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @test
     */
    public function shouldRecordVisitor()
    {
        $response = $this->post('/api/visitor/set', [
            'device' => 'android',
            'connection' => false   
        ], ['HTTP_GGP_TEST_IP' => '1.1.1.1']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('visitors', ['ip_address' => '1.1.1.1', 'device' => 'android']);
    }
}
