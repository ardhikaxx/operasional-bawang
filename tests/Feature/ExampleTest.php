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
    public function test_the_application_redirects_unauthenticated_users_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_login_page_returns_a_successful_response(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_laporan_laba_rugi_returns_successful_response(): void
    {
        $owner = \App\Models\User::factory()->create(['role' => 'owner']);
        $response = $this->actingAs($owner)->get('/laporan/laba-rugi?bulan=06&tahun=2026');
        $response->assertStatus(200);
    }
}
