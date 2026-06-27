<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAkses;
use App\Models\RiwayatLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function showLoginDosen()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login-dosen');
    }

    public function showLoginMahasiswa()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login-mahasiswa');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        // If a role is provided in the login form, require that role on authentication
        $role = $request->input('role');
        if ($role && in_array($role, ['admin', 'dosen', 'user'])) {
            if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password'], 'role' => $role])) {
                $request->session()->regenerate();
                return redirect('/')->with('success', 'Login berhasil sebagai ' . $role . '!');
            }
            return back()->withErrors(['email' => 'Email atau password salah / bukan akun ' . $role . '.'])->withInput();
        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function loginDosen(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password'], 'role' => 'dosen'])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Login dosen berhasil!');
        }

        return back()->withErrors(['email' => 'Email atau password salah / bukan akun dosen.'])->withInput();
    }

    public function loginMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password'], 'role' => 'user'])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['email' => 'Email atau password salah / bukan akun mahasiswa.'])->withInput();
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function showRegisterDosen()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register-dosen');
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'user');
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        if ($role === 'dosen') {
            $rules['nisn'] = 'required|string|max:50';
        } else {
            $rules['nim'] = 'required|string|max:50';
            $rules['prodi'] = 'required|string|max:100';
            $rules['semester'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role === 'dosen' ? 'dosen' : 'user',
        ];

        if ($role === 'dosen') {
            $userData['nisn'] = $validated['nisn'];
            $userData['nim'] = null;
            $userData['prodi'] = null;
            $userData['semester'] = null;
        } else {
            $userData['nim'] = $validated['nim'];
            $userData['prodi'] = $validated['prodi'];
            $userData['semester'] = $validated['semester'];
            $userData['nisn'] = null;
        }

        $user = User::create($userData);

        if ($user->role === 'dosen') {
            RiwayatLog::create([
                'event' => 'Registrasi Dosen',
                'detail' => sprintf('Dosen %s (%s) - NISN: %s mendaftar.', $user->name, $user->email, $user->nisn),
            ]);
        } else {
            RiwayatLog::create([
                'event' => 'Registrasi',
                'detail' => sprintf('User %s (%s) - NIM: %s, Prodi: %s, Semester: %s mendaftar.', $user->name, $user->email, $user->nim, $user->prodi, $user->semester),
            ]);
        }

        Auth::login($user);
        
        if ($user->role === 'dosen') {
            return redirect('/')->with('success', 'Registrasi dosen berhasil!');
        }
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di SimpleLab.');
    }

    public function registerDosen(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nisn' => 'required|string|max:50',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dosen',
            'nisn' => $validated['nisn'],
        ]);

        RiwayatLog::create([
            'event' => 'Registrasi Dosen',
            'detail' => sprintf('Dosen %s (%s) - NISN: %s mendaftar.', $user->name, $user->email, $user->nisn),
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Registrasi dosen berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logout berhasil.');
    }

    public function createDosenByAdmin(Request $request)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dosen',
        ]);

        // Log admin-created dosen account
        RiwayatLog::create([
            'event' => 'Admin Buat Dosen',
            'detail' => sprintf('Admin %s membuat akun dosen %s (%s).', $user->name, $newUser->name, $newUser->email),
        ]);

        // No LogAkses entry for admin-created akun dosen to avoid activity noise

        return back()->with('success', 'Akun dosen berhasil dibuat.');
    }
}
