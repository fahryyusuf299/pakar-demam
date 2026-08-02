<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminLog;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            AdminLog::record('LOGIN', 'Admin berhasil melakukan login ke sistem.');

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::guard('admin')->user()->name);
        }

        return back()->withErrors([
            'email' => 'Kredensial email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            AdminLog::record('LOGOUT', 'Admin keluar dari sesi aplikasi.');
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('info', 'Anda telah keluar dari sesi admin.');
    }
}
