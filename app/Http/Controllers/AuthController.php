<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Konsumen;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'konsumen') return redirect('/katalog');
            return redirect('/pemesanan-supplier');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Username atau password salah.']);
        }

        // Login langsung pakai Auth::login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->role === 'konsumen') {
            return redirect()->intended('/katalog');
        }

        return redirect()->intended('/pemesanan-supplier');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_konsumen' => 'required|string|max:100',
            'no_hp'         => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
            'username'      => 'required|string|max:50|unique:users,username',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        $konsumen = Konsumen::create([
            'nama_konsumen' => $request->nama_konsumen,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
            'limit_kredit'  => 0,
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_konsumen,
            'username'     => $request->username,
            'password'     => bcrypt($request->password),
            'role'         => 'konsumen',
            'id_konsumen'  => $konsumen->id_konsumen,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/katalog')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $request->nama_konsumen);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}