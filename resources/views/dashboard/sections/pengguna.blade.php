<div id="aktifitas" class="tab-content">
    <div class="stats-grid">
        <div class="stat-card">
            <span class="label">Aktivitas Terbaru</span>
            <span class="value">{{ isset($recentActivities) ? $recentActivities->count() : 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="label">Peminjaman Terbaru</span>
            <span class="value">{{ isset($recentLoans) ? $recentLoans->count() : 0 }}</span>
        </div>
    </div>

    <div class="list-card" style="margin-top:1rem;">
        <h3>Aktivitas Sistem</h3>
        @if(!isset($recentActivities) || $recentActivities->isEmpty())
            <div class="empty-state">
                <i data-lucide="activity" size="48"></i>
                <p>Belum ada aktivitas tercatat.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $act)
                            @php
                                $displayActWhen = '-';
                                if(!empty($act->created_at)) {
                                    try {
                                        if($act->created_at instanceof \Illuminate\Support\Carbon) {
                                            $displayActWhen = $act->created_at->isoFormat('D MMM YYYY, HH:mm');
                                        } else {
                                            $displayActWhen = \Illuminate\Support\Carbon::parse($act->created_at)->isoFormat('D MMM YYYY, HH:mm');
                                        }
                                    } catch (\Exception $e) {
                                        $displayActWhen = '-';
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $displayActWhen }}</td>
                                <td>{{ $act->user->name ?? ($act->user_id ? 'User#'.$act->user_id : '-') }}</td>
                                <td>{{ $act->action }}</td>
                                <td style="max-width:300px;word-wrap:break-word;white-space:normal;">{{ $act->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card form-card" style="margin-top:1.5rem;">
        <h3>Scan Masuk/Keluar Aset</h3>
        <form action="/rfid/track" method="POST">
            @csrf
            <div class="input-group" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <input type="text" name="tag_uid" class="input-custom rfid-scan-input" placeholder="Scan UID Tag RFID Aset" autocomplete="off" required>
                <button type="button" class="btn-scan" style="padding:6px 10px;" onclick="focusAndNotify(this.closest('form').querySelector('[name=tag_uid]'), 'Klik field Tag lalu pindai tag RFID.')">Scan</button>
                <select name="lokasi" class="input-custom" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>
            <button class="btn-primary" type="submit">Update Lokasi</button>
            <p class="hint-text">Gunakan tombol Scan untuk langsung memfokuskan field UID dan membaca tag.</p>
        </form>
    </div>

    <div class="list-card" style="margin-top:1.5rem;">
        <h3>Peminjaman Terbaru</h3>
        @if(!isset($recentLoans) || $recentLoans->isEmpty())
            <div class="empty-state">
                <i data-lucide="clock" size="48"></i>
                <p>Belum ada peminjaman terbaru.</p>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Barang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $loan)
                            @php
                                $displayLoanWhen = '-';
                                if(!empty($loan->created_at)) {
                                    try {
                                        if($loan->created_at instanceof \Illuminate\Support\Carbon) {
                                            $displayLoanWhen = $loan->created_at->isoFormat('D MMM YYYY, HH:mm');
                                        } else {
                                            $displayLoanWhen = \Illuminate\Support\Carbon::parse($loan->created_at)->isoFormat('D MMM YYYY, HH:mm');
                                        }
                                    } catch (\Exception $e) {
                                        $displayLoanWhen = '-';
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $displayLoanWhen }}</td>
                                <td>{{ $loan->user->name ?? 'User Terhapus' }}</td>
                                <td>{{ $loan->barang->name ?? 'Barang Terhapus' }}</td>
                                <td>{{ ucfirst($loan->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
