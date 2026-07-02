<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\TagRfid;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OverdueLoansTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_overdue_loans_warning(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'name' => 'Overdue Equipment',
            'kategori' => 'Lab',
            'kondisi' => 'Baik',
            'status' => 'borrowed',
        ]);

        $tag = TagRfid::create([
            'uid' => 'TEST-OVERDUE-001',
            'barang_id' => $barang->id,
        ]);

        // Create an overdue loan (due date in the past)
        $overdueLoan = Peminjaman::create([
            'user_id' => $user->id,
            'barang_id' => $barang->id,
            'tag_rfid_id' => $tag->id,
            'started_at' => now()->subDays(5),
            'due_date' => now()->subDays(2),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('Peringatan: Barang Belum Dikembalikan!');
        $response->assertSeeText('Overdue Equipment');
        $response->assertSeeText('hari terlambat');
    }

    public function test_dashboard_does_not_show_warning_for_non_overdue_loans(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'name' => 'Active Equipment',
            'kategori' => 'Lab',
            'kondisi' => 'Baik',
            'status' => 'borrowed',
        ]);

        $tag = TagRfid::create([
            'uid' => 'TEST-ACTIVE-001',
            'barang_id' => $barang->id,
        ]);

        // Create a non-overdue loan (due date in the future)
        $activeLoan = Peminjaman::create([
            'user_id' => $user->id,
            'barang_id' => $barang->id,
            'tag_rfid_id' => $tag->id,
            'started_at' => now(),
            'due_date' => now()->addDays(3),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSeeText('Peringatan: Barang Belum Dikembalikan!');
    }

    public function test_badge_shows_overdue_status(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'name' => 'Overdue Item',
            'kategori' => 'Lab',
            'kondisi' => 'Baik',
            'status' => 'borrowed',
        ]);

        $tag = TagRfid::create([
            'uid' => 'TEST-BADGE-001',
            'barang_id' => $barang->id,
        ]);

        Peminjaman::create([
            'user_id' => $user->id,
            'barang_id' => $barang->id,
            'tag_rfid_id' => $tag->id,
            'started_at' => now()->subDays(10),
            'due_date' => now()->subDays(3),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('Overdue');
    }
}
