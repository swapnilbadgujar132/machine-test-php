<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_urls_use_https_when_forwarded_by_the_proxy(): void
    {
        $this->withHeader('X-Forwarded-Proto', 'https')
            ->get('/')
            ->assertOk()
            ->assertSee('https://localhost:8000/materials', false);
    }
}
