<div id="laporan" class="tab-content">
    <div class="info-box">
        <i data-lucide="file-text" style="width: 20px;"></i>
        <p>Ringkasan aktivitas RFID, termasuk autentikasi kartu, pelacakan aset, dan peminjaman.</p>
    </div>

    <div class="list-card">
        <h3>Log Aktivitas Terakhir</h3>
        @if($recentActivities->isEmpty())
            <div class="empty-state">
                <i data-lucide="clock" size="48"></i>
                <p>Belum ada aktivitas RFID.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Aksi</th>
                            <th>User / Kartu</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                            <tr>
                                @php
                                    $displayWhen = '-';
                                    if(!empty($activity->created_at)) {
                                        try {
                                            if($activity->created_at instanceof \Illuminate\Support\Carbon) {
                                                $displayWhen = $activity->created_at->format('d M Y H:i');
                                            } else {
                                                $displayWhen = \Illuminate\Support\Carbon::parse($activity->created_at)->format('d M Y H:i');
                                            }
                                        } catch (\Exception $e) {
                                            $displayWhen = '-';
                                        }
                                    }
                                @endphp
                                <td>{{ $displayWhen }}</td>
                                <td>{{ $activity->action }}</td>
                                <td>{{ $activity->user->name ?? ($activity->rfidCard->uid ?? '-') }}</td>
                                <td>{{ $activity->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card form-card mt-4">
        <div class="card-header">
            <i data-lucide="bar-chart-2" style="width: 18px; color: var(--text-muted);"></i>
            <h3>Rekap Penggunaan RFID</h3>
        </div>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Sistem menghasilkan laporan berdasarkan data log RFID untuk audit dan monitoring.</p>
    </div>
</div>
