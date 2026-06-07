@if(auth()->check() && auth()->user()->role === 'admin')
<section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
    <h2 class="text-lg font-medium mb-2">Laporan Admin</h2>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Lihat riwayat peminjaman barang.</p>
    <div class="flex gap-3">
        <a href="{{ route('admin.laporan.peminjaman') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Laporan Peminjaman Barang</a>
    </div>
</section>
@else
<section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Anda tidak memiliki akses ke laporan ini.</p>
</section>
@endif
