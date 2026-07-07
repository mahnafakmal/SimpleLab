<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'user',
            'nim' => '20230001',
            'prodi' => 'Teknik Informatika',
            'semester' => '4',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Profil Mahasiswa');
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@example.com');
        $response->assertSee('20230001');
    }
}
