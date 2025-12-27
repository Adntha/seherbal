<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // Mengarah ke file resources/views/admin/login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
        //     // Jika berhasil login, arahkan ke dashboard admin
            return redirect()->intended('/admin/dashboard');
        }
        //     // Membersihkan catatan navigasi sebelumnya agar tidak balik ke home
        //     $request->session()->regenerate();

        //     $request->session()->forget('url.intended');
            
        //     // GUNAKAN redirect() langsung ke URL, jangan gunakan intended()
        //     return redirect('/admin/dashboard');
        // }
        // Jika gagal, balikkan ke halaman login dengan pesan error
        return back()->withErrors(['email' => 'Email atau Password salah!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}   
