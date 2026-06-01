<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLogin()
    {
        return view('Login.Login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $validated['username'])->orWhere('username', $validated['username'])->first();

        // Coba login menggunakan bcrypt dulu, lalu fallback ke md5 hash
        if ($user && (\Illuminate\Support\Facades\Hash::check($validated['password'], $user->password) || md5($validated['password']) === $user->password)) {
            Auth::login($user);
            // Jika login berhasil, buat session dan redirect ke dashboard
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        // Jika login gagal, redirect kembali ke login dengan pesan error
        return redirect('/login')->with('error', 'Username atau password salah!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
