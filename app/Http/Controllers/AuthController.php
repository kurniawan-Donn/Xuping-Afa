<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        try {
            $authenticated = Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'));
        } catch (\RuntimeException $e) {
            // Kolom password di database bukan hash bcrypt (misalnya diedit manual lewat phpMyAdmin)
            Log::warning('Login gagal: format password di database tidak valid.', ['email' => $request->email]);
            $authenticated = false;
        }

        if ($authenticated) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang.');
        }
        return back()->with('error', 'Email atau password yang Anda masukkan salah.')->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
