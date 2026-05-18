<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SimpleLab</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/welcome.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col items-center p-6 lg:p-8 min-h-screen">
    @include('partials.navbar')
    <main class="w-full max-w-4xl">
        <h1 class="text-2xl font-semibold mb-6">Dashboard Overview</h1>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Ringkasan -->
            <section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-medium mb-2">Ringkasan</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Ringkasan singkat tentang statistik utama.</p>
            </section>
            <!-- Peralatan -->
            <section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-medium mb-2">Peralatan</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Daftar peralatan yang tersedia dan statusnya.</p>
            </section>
            <!-- Peminjaman -->
            <section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-medium mb-2">Peminjaman</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Ringkasan peminjaman terkini.</p>
            </section>
            <!-- Pengguna -->
            <section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-medium mb-2">Pengguna</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Statistik akun pengguna.</p>
            </section>
            <!-- Auto-Admin -->
            <section class="bg-white dark:bg-[#161615] p-4 rounded-lg shadow-md col-span-2 lg:col-span-1">
                <h2 class="text-lg font-medium mb-2">Auto‑Admin</h2>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Fitur otomatisasi admin untuk mengelola sistem.</p>
            </section>
        </div>
    </main>
</body>
</html>
