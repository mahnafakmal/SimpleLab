<!-- resources/views/dashboard/sections/admin_peminjaman_lab.blade.php -->
<div class="panel-card">
    <div class="panel-header">
        <div class="panel-title">
            <i data-lucide="calendar"></i>
            <h3>Admin - Persetujuan Peminjaman Lab</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Pemohon</th>
                    <th>Laboratorium</th>
                    <th>Keperluan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Status</th>
                    <th>Catatan / Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labLoans ?? [] as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->user->name ?? '-' }}</strong><br>
                            <small style="color: #666;">
                                @if($item->user?->role === 'user')
                                    Mahasiswa
                                @elseif($item->user?->role === 'dosen')
                                    Dosen
                                @else
                                    {{ ucfirst($item->user?->role ?? '') }}
                                @endif
                            </small>
                        </td>
                        <td>{{ $item->nama_lab }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}<br>
                            <small style="color: #666;">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <span class="badge badge-{{ $item->status == 'disetujui' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <form action="{{ route('peminjaman.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-primary" style="background-color: #28a745; border-color: #28a745; padding: 0.4rem 0.8rem; font-size: 0.75rem; color: #fff; border-radius: 4px; cursor: pointer;" onclick="return confirm('Setujui peminjaman lab ini?')">
                                            Setujui
                                        </button>
                                    </form>

                                    <button class="btn-primary" style="background-color: #dc3545; border-color: #dc3545; padding: 0.4rem 0.8rem; font-size: 0.75rem; color: #fff; border-radius: 4px; cursor: pointer;" type="button" onclick="toggleRejectPanel({{ $item->id }})">
                                        Tolak
                                    </button>
                                </div>

                                <div id="reject-panel-{{ $item->id }}" style="display: none; margin-top: 0.5rem; background: #f8f9fa; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                                    <form action="{{ route('peminjaman.reject', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="text" name="catatan_admin" class="form-control-custom" placeholder="Alasan penolakan..." style="width: 100%; margin-bottom: 0.5rem; font-size: 0.8rem; padding: 0.4rem; border: 1px solid #ccc; border-radius: 4px;" required>
                                        <div style="display: flex; justify-content: flex-end; gap: 0.25rem;">
                                            <button type="button" class="btn-primary" style="background-color: #6c757d; border-color: #6c757d; padding: 0.2rem 0.5rem; font-size: 0.7rem; color: #fff; border-radius: 4px; cursor: pointer;" onclick="toggleRejectPanel({{ $item->id }})">Batal</button>
                                            <button type="submit" class="btn-primary" style="background-color: #dc3545; border-color: #dc3545; padding: 0.2rem 0.5rem; font-size: 0.7rem; color: #fff; border-radius: 4px; cursor: pointer;">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <span style="font-size: 0.85rem; color: #666; font-style: italic;">{{ $item->catatan_admin ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada pengajuan peminjaman laboratorium.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleRejectPanel(id) {
        var panel = document.getElementById('reject-panel-' + id);
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }
</script>
