@if(auth()->check() && auth()->user()->role === 'admin')
<section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
    <h2 class="text-lg font-medium mb-2">Laporan Admin</h2>
    <div class="list-card" style="margin-top:0.5rem;">
        <h3>Daftar Laporan</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Laporan</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laporan Peminjaman Barang</td>
                        <td>Riwayat peminjaman barang oleh pengguna</td>
                        <td>{{ isset($allLoans) ? $allLoans->count() : '-' }}</td>
                        <td><a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-primary">Lihat</a></td>
                    </tr>
                    <tr>
                        <td>Laporan Registrasi</td>
                        <td>Daftar akun pengguna baru</td>
                        <td>{{ isset($registrationsCount) ? $registrationsCount : '-' }}</td>
                        <td><a href="{{ route('admin.laporan.registrasi') }}" class="btn btn-primary">Lihat</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@else
<section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Anda tidak memiliki akses ke laporan ini.</p>
</section>
@endif
