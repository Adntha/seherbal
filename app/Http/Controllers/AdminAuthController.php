<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // ✅ Generate Sanctum token untuk API
            $token = $user->createToken('admin-token')->plainTextToken;
            
            // ✅ Simpan token ke session untuk diambil frontend
            $request->session()->put('admin_token', $token);
            
            // Redirect ke dashboard
            return redirect()->intended('/admin/dashboard');
        }

        // Jika gagal, balikkan ke halaman login dengan pesan error
        return back()->withErrors(['email' => 'Email atau Password salah!']);
    }

    public function logout(Request $request)
    {
        // Hapus semua token user
        $request->user()->tokens()->delete();
        
        Auth::logout();
        return redirect('/admin/login');
    }
}
