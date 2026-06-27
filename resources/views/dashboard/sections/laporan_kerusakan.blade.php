<div>
    <div class="stats-grid">
        <div class="stat-card" data-href="{{ route('damage-reports.index') }}">
            <span class="label">TOTAL LAPORAN</span>
            <span class="value">{{ $allReports->count() }}</span>
        </div>
        <div class="stat-card" data-href="{{ route('damage-reports.index') }}?status=pending">
            <span class="label">PENDING</span>
            <span class="value" style="color: #f59e0b;">{{ $allReports->where('status', 'pending')->count() }}</span>
        </div>
        <div class="stat-card" data-href="{{ route('damage-reports.index') }}?status=proses">
            <span class="label">DALAM PROSES</span>
            <span class="value" style="color: #3b82f6;">{{ $allReports->where('status', 'proses')->count() }}</span>
        </div>
        <div class="stat-card" data-href="{{ route('damage-reports.index') }}?status=selesai">
            <span class="label">SELESAI DIPERBAIKI</span>
            <span class="value" style="color: #22c55e;">{{ $allReports->where('status', 'selesai')->count() }}</span>
        </div>
    </div>

    <div class="list-card">
        <h3>Daftar Laporan Kerusakan Barang</h3>
        @if($allReports->isEmpty())
            <div class="empty-state">
                <i data-lucide="alert-circle" size="48"></i>
                <p>Belum ada laporan kerusakan barang.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Barang</th>
                            <th>Pelapor</th>
                            <th>Deskripsi Kerusakan</th>
                            <th>Status</th>
                            <th>Tanggal Lapor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allReports as $report)
                            @php
                                $img = null;
                                $basePath = public_path('images/barangs/');
                                $candidates = [];
                                if(isset($report->barang) && !empty($report->barang->image)) $candidates[] = $report->barang->image;
                                if(isset($report->barang)) {
                                    $candidates[] = $report->barang->id . '.jpg';
                                    $candidates[] = $report->barang->id . '.png';
                                    $candidates[] = \Illuminate\Support\Str::slug($report->barang->name) . '.jpg';
                                    $candidates[] = \Illuminate\Support\Str::slug($report->barang->name) . '.png';
                                }
                                foreach($candidates as $c) {
                                    if(!empty($c) && file_exists($basePath . $c)) { $img = $c; break; }
                                }
                            @endphp
                            <tr>
                                <td>
                                    @php
                                        $imgSrc = null;
                                        // If earlier candidate search found a filename
                                        if(!empty($img)) {
                                            // Normalize: if candidate already includes a path, use it; otherwise assume images/barangs/
                                            if(\Illuminate\Support\Str::startsWith($img, ['images/', '/'])) {
                                                $imgSrc = '/' . ltrim($img, '/');
                                            } else {
                                                $imgSrc = '/images/barangs/' . ltrim($img, '/');
                                            }
                                        } elseif(isset($report->barang) && !empty($report->barang->image)) {
                                            // Use stored image path (could be 'images/...') or URL
                                            $stored = $report->barang->image;
                                            if(\Illuminate\Support\Str::startsWith($stored, ['http://', 'https://'])) {
                                                $imgSrc = $stored;
                                            } else {
                                                $imgSrc = '/' . ltrim($stored, '/');
                                            }
                                        }
                                    @endphp

                                    @if($imgSrc)
                                        <img src="{{ $imgSrc }}" alt="{{ $report->barang->name ?? 'Barang' }}" style="width:56px;height:40px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:56px;height:40px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                            <i data-lucide="box" style="width:16px;height:16px;color:#94a3b8"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $report->barang->name ?? 'Barang Terhapus' }}</strong>
                                    <div style="font-size:11px;color:#94a3b8;">Kategori: {{ $report->barang->kategori ?? '-' }}</div>
                                </td>
                                <td>
                                    {{ $report->user->name ?? 'User Terhapus' }}
                                    <div>
                                        @if(isset($report->user))
                                            @php
                                                $roleClass = $report->user->role === 'dosen' ? 'role-badge-dosen' : 'role-badge-mahasiswa';
                                                $roleLabel = ucfirst($report->user->role === 'user' ? 'mahasiswa' : $report->user->role);
                                            @endphp
                                            <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                                            <style>
                                                .role-badge{font-size:10px;padding:2px 6px;border-radius:99px;font-weight:600;display:inline-block}
                                                .role-badge-dosen{background:#fff7ed;color:#ea580c}
                                                .role-badge-mahasiswa{background:#f0fdf4;color:#16a34a}
                                            </style>
                                        @endif
                                    </div>
                                </td>
                                <td style="max-width:250px;word-wrap:break-word;white-space:normal;">
                                    {{ $report->deskripsi }}
                                </td>
                                <td>
                                    @if($report->status === 'pending')
                                        <span style="padding:4px 8px;border-radius:99px;font-size:11px;font-weight:600;background:#fffbeb;color:#d97706;">Pending</span>
                                    @elseif($report->status === 'proses')
                                        <span style="padding:4px 8px;border-radius:99px;font-size:11px;font-weight:600;background:#eff6ff;color:#2563eb;">Proses Perbaikan</span>
                                    @else
                                        <span style="padding:4px 8px;border-radius:99px;font-size:11px;font-weight:600;background:#f0fdf4;color:#16a34a;">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $displayReportDate = '-';
                                        if(!empty($report->created_at)) {
                                            try {
                                                if($report->created_at instanceof \Illuminate\Support\Carbon) {
                                                    $displayReportDate = $report->created_at->isoFormat('D MMM YYYY, HH:mm');
                                                } else {
                                                    $displayReportDate = \Illuminate\Support\Carbon::parse($report->created_at)->isoFormat('D MMM YYYY, HH:mm');
                                                }
                                            } catch (\Exception $e) {
                                                $displayReportDate = '-';
                                            }
                                        }
                                    @endphp
                                    {{ $displayReportDate }} WIB
                                </td>
                                <td>
                                    @if($report->status === 'pending')
                                        <div style="display:flex;gap:6px;">
                                            <form action="{{ route('admin.laporan.kerusakan.status', $report->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="proses">
                                                <button type="submit" class="btn-primary" style="padding:4px 8px;font-size:11px;border-radius:6px;background:#3b82f6;">Mulai Perbaikan</button>
                                            </form>
                                            <form action="{{ route('admin.laporan.kerusakan.status', $report->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="btn-primary" style="padding:4px 8px;font-size:11px;border-radius:6px;background:#22c55e;">Selesai</button>
                                            </form>
                                        </div>
                                    @elseif($report->status === 'proses')
                                        <form action="{{ route('admin.laporan.kerusakan.status', $report->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="btn-primary" style="padding:4px 8px;font-size:11px;border-radius:6px;background:#22c55e;">Tandai Selesai</button>
                                        </form>
                                    @else
                                        <span style="color:#22c55e;font-weight:600;font-size:12px;display:flex;align-items:center;gap:4px;">
                                            <i data-lucide="check-circle" style="width:14px;height:14px;"></i> Diperbaiki
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
