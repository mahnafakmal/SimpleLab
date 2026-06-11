@extends('layouts.simplelab')

@section('content')
<div class="login-page">
    <div class="login-card">
        <div class="login-left">
            <div class="logo-circle">SL</div>
            <h2>SIMPLELAB</h2>
            <p>Sistem Manajemen Peralatan Laboratorium<br>Universitas Muhammadiyah Semarang</p>
        </div>
        <div class="login-right">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label>Username / NIM / NIDN</label>
                <input name="username" type="text" placeholder="Username" required>
                <label>Password</label>
                <input name="password" type="password" placeholder="Password" required>

                <label style="margin-top:8px; display:block; font-weight:600;">Pilih Role</label>
                <div style="display:flex;gap:12px;margin-bottom:12px">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="radio" name="role" value="admin" checked>
                        <span style="font-weight:600">Admin / Laboran</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="radio" name="role" value="dosen">
                        <span style="font-weight:600">Dosen</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="radio" name="role" value="mahasiswa">
                        <span style="font-weight:600">Mahasiswa</span>
                    </label>
                </div>

                <div style="margin-top:18px;display:flex;gap:10px;">
                    <button class="btn" type="reset">Batal</button>
                    <button class="btn-primary" type="submit">LOGIN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
