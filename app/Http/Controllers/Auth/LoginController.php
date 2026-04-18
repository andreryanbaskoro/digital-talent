<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LamaranPekerjaan;

class LoginController extends Controller
{
    // ================= FORM LOGIN =================
    public function showLoginForm()
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    // ================= PROSES LOGIN =================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // ================= CEK STATUS =================
            if ($user->status !== 'aktif') {
                Auth::logout();
                return back()->with('error', 'Akun tidak aktif');
            }

            // ================= ROLE: PENCAKER =================
            if ($user->peran === 'pencaker') {

                $profil = $user->profilPencariKerja;

                // cek apakah sudah pernah melamar
                $punyaLamaran = $profil
                    ? LamaranPekerjaan::where('id_pencari_kerja', $profil->id_pencari_kerja)->exists()
                    : false;

                // ❌ belum ada lamaran → landing
                if (!$punyaLamaran) {
                    return redirect()->route('landing');
                }

                // ✅ sudah ada lamaran → dashboard pencaker
                return redirect()->route('pencaker.dashboard');
            }

            // ================= ROLE: PERUSAHAAN =================
            if ($user->peran === 'perusahaan') {
                return redirect()->route('perusahaan.dashboard');
            }

            // ================= ROLE: DISNAKER =================
            if ($user->peran === 'disnaker') {
                return redirect()->route('disnaker.dashboard');
            }

            // fallback (kalau ada role lain)
            return redirect()->route('landing');
        }

        return back()->with('error', 'Email atau kata sandi salah');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $user = Auth::user();
        $previousUrl = url()->previous();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ================= LOGIC KHUSUS =================
        if ($user && $user->peran === 'pencaker') {

            // kalau logout dari landing → balik ke landing
            if (str_contains($previousUrl, route('landing'))) {
                return redirect()->route('landing');
            }

            // selain itu → login
            return redirect()->route('login');
        }

        // role lain → login
        return redirect()->route('login');
    }
}
