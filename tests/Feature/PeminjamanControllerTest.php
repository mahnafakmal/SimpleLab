<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_select_a_borrow_time_range_when_borrowing_equipment(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'name' => 'Microscope',
            'kategori' => 'Lab',
            'kondisi' => 'Baik',
            'status' => 'available',
        ]);

        $response = $this->actingAs($user)->post('/web/peminjaman/alat', [
            'barang_id' => $barang->id,
            'waktu_mulai' => '2026-07-10T08:00',
            'waktu_selesai' => '2026-07-10T10:00',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $peminjaman = Peminjaman::where('user_id', $user->id)->where('barang_id', $barang->id)->latest()->first();

        $this->assertNotNull($peminjaman);
        $this->assertEquals('2026-07-10 08:00:00', $peminjaman->started_at->format('Y-m-d H:i:s'));
        $this->assertEquals('2026-07-10 10:00:00', $peminjaman->due_date->format('Y-m-d H:i:s'));
    }
}
