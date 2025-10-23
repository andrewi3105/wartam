<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Menampilkan form login
    public function showLogin()
    {
        return view('login');
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        // ✅ Jika username tidak ada atau password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->with('error', 'Username atau password salah.') // 🔹 gunakan session('error')
                ->withInput();
        }

        // ❌ Cegah login jika nonaktif
        if ($user->status !== 'aktif') {
            return back()
                ->with('error', 'Akun ini nonaktif. Silakan hubungi admin.') // 🔹 gunakan session('error')
                ->withInput();
        }

        // ✅ Login sukses
        Auth::login($user);

        $user->update(['last_login' => now()]);

        return redirect()->route('dashboard');
    }

    // 🔹 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
