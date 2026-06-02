@if(auth()->check() && auth()->user()->role === 'admin')
<section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
    <h2 class="text-lg font-medium mb-2">Monitoring Ruangan (Senin - Jumat)</h2>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Tampilan grid waktu 07:00 - 21:00, klik sel untuk detail peminjaman.</p>

    @php
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat'];
        $hours = [];
        for ($h = 7; $h <= 21; $h++) {
            $hours[] = sprintf('%02d:00', $h);
        }
        // index bookings by day for faster lookup
        $bookingsByDay = [];
        if(isset($allRooms)){
            foreach($allRooms as $b){
                if(in_array($b->hari, $days)){
                    $bookingsByDay[$b->hari][] = $b;
                }
            }
        }
    @endphp

    <div style="overflow:auto;">
        <table class="monitor-grid" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:110px;border:1px solid #e5e7eb;padding:8px;background:#f8fafc">Jam</th>
                    @foreach($days as $day)
                        <th style="border:1px solid #e5e7eb;padding:8px;background:#f8fafc">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($hours as $hour)
                    <tr>
                        <td style="border:1px solid #eee;padding:6px;font-weight:600">{{ $hour }}</td>
                        @foreach($days as $day)
                            @php
                                $cellBookings = collect($bookingsByDay[$day] ?? [])->filter(function($bk) use($hour){
                                    // booking covers this hour if jam_mulai <= hour < jam_selesai
                                    return ($bk->jam_mulai <= $hour) && ($bk->jam_selesai > $hour);
                                });
                            @endphp
                            <td style="border:1px solid #eee;padding:6px;vertical-align:top;min-width:140px;">
                                @if($cellBookings->isEmpty())
                                    <div style="color:#6b7280;font-size:13px;">—</div>
                                @else
                                    @foreach($cellBookings as $bk)
                                        <div style="background:#eef2ff;border-left:4px solid #6366f1;padding:6px;margin-bottom:6px;border-radius:6px;font-size:13px;">
                                            <div style="font-weight:600">{{ $bk->nama_ruangan }}</div>
                                            <div style="font-size:12px;color:#374151">{{ $bk->jam_mulai }} - {{ $bk->jam_selesai }}</div>
                                            <div style="font-size:12px;color:#374151">{{ $bk->keperluan }}</div>
                                            <div style="font-size:12px;color:#475569">oleh {{ optional($bk->user)->name ?? 'N/A' }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@else
<section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Anda tidak memiliki akses ke monitoring ruangan.</p>
</section>
@endif
