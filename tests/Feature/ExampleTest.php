<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Buy the Developer a Coffee');
        $response->assertSee('https://res.cloudinary.com/wyofiygs/image/upload/v1786680470/photo_6339320443151520448_x_ecob0p.jpg');
        $response->assertSee('Support Invitr ☕');
    }
}
