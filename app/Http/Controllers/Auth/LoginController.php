<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\LamaranPekerjaan;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;
use App\Models\ProfilPencariKerja;
use App\Models\ProfilPerusahaan;
use App\Models\PasswordReset;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // ================= FORM LOGIN =================
    public function showLoginForm()
    {
        return view('auth.login', [
            'title' => 'Masuk'
        ]);
    }

    // ================= FORM SIGN UP =================
    public function showRegisterForm()
    {
        return view('auth.register', [
            'title' => 'Daftar'
        ]);
    }

    // ================= PROSES SIGN UP =================
    public function register(Request $request)
    {
        // ================= BASE RULE =================
        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pengguna,email',
            'password' => 'required|string|min:8|confirmed',
            'peran' => ['required', Rule::in(['pencaker', 'perusahaan', 'disnaker'])],
        ];

        // ================= ROLE: PENCAKER =================
        if ($request->peran === 'pencaker') {
            $rules += [
                'nik' => 'required|digits:16',
                'nama_lengkap' => 'required|max:150',
                'tanggal_lahir' => 'required|date|before:today',
                'jenis_kelamin' => 'required|in:L,P',
                'nomor_hp' => 'required|regex:/^[0-9]{10,15}$/',
                'alamat' => 'required|max:255',
            ];
        }

        // ================= ROLE: PERUSAHAAN =================
        if ($request->peran === 'perusahaan') {
            $rules += [
                'nama_perusahaan' => 'required|max:150',
                'nib' => 'required|digits_between:8,20',
                'npwp' => 'nullable|digits_between:15,20',
                'nomor_telepon' => 'required|regex:/^[0-9]{10,15}$/',
                'website' => 'nullable|url',
            ];
        }

        // ================= ROLE: DISNAKER =================
        if ($request->peran === 'disnaker') {
            $rules += [
                'nip' => 'required|string|max:30|unique:pengguna,nip',
            ];
        }

        // ================= VALIDATE (INDONESIA MESSAGE) =================
        $messages = [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'confirmed' => 'Konfirmasi password tidak sesuai.',
            'digits' => ':attribute harus :digits digit.',
            'digits_between' => ':attribute harus antara :min sampai :max digit.',
            'regex' => 'Format :attribute tidak valid.',
            'in' => ':attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal.',
            'before' => ':attribute harus sebelum hari ini.',
            'url' => 'Format :attribute tidak valid.',
        ];

        $attributes = [
            'nama' => 'Nama akun',
            'email' => 'Email',
            'password' => 'Password',
            'peran' => 'Peran',

            'nip' => 'NIP',

            'nik' => 'NIK',
            'nama_lengkap' => 'Nama lengkap',
            'tanggal_lahir' => 'Tanggal lahir',
            'jenis_kelamin' => 'Jenis kelamin',
            'nomor_hp' => 'Nomor HP',
            'alamat' => 'Alamat',

            'nama_perusahaan' => 'Nama perusahaan',
            'nib' => 'NIB',
            'npwp' => 'NPWP',
            'nomor_telepon' => 'Nomor telepon',
            'website' => 'Website',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        DB::beginTransaction();

        try {

            // ================= SIMPAN USER =================
            $user = Pengguna::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'kata_sandi' => Hash::make($request->password),
                'peran' => $request->peran,
                'status' => 'aktif',
                'nip' => $request->peran === 'disnaker' ? $request->nip : null,
            ]);

            // ================= PENCAKER =================
            if ($request->peran === 'pencaker') {
                ProfilPencariKerja::create([
                    'id_pengguna' => $user->id_pengguna,
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                    'nomor_hp' => $request->nomor_hp,
                    'email' => $request->email,
                ]);
            }

            // ================= PERUSAHAAN =================
            if ($request->peran === 'perusahaan') {
                ProfilPerusahaan::create([
                    'id_pengguna' => $user->id_pengguna,
                    'nama_perusahaan' => $request->nama_perusahaan,
                    'nib' => $request->nib,
                    'npwp' => $request->npwp,
                    'nomor_telepon' => $request->nomor_telepon,
                    'website' => $request->website,
                ]);
            }

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi berhasil, silahkan masuk');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
        ], [
            'email' => 'Email',
            'password' => 'Password',
        ]);

        // 🔥 CEK USER TERLEBIH DAHULU
        $user = \App\Models\Pengguna::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()
                ->with('error', 'Email tidak ditemukan.');
        }

        // 🔥 CEK PASSWORD
        if (!\Hash::check($request->password, $user->kata_sandi)) {
            return back()->withInput()
                ->with('error', 'Password yang Anda masukkan salah.');
        }

        // 🔥 CEK STATUS AKUN
        if ($user->status !== 'aktif') {
            return back()->with('error', 'Akun Anda tidak aktif.');
        }

        // 🔥 LOGIN MANUAL (lebih aman dari Auth::attempt)
        Auth::login($user);

        $request->session()->regenerate();

        // ================= ROLE REDIRECT =================
        if ($user->peran === 'pencaker') {

            $profil = $user->profilPencariKerja;

            $punyaLamaran = $profil
                ? \App\Models\LamaranPekerjaan::where('id_pencari_kerja', $profil->id_pencari_kerja)->exists()
                : false;

            if (!$punyaLamaran) {
                return redirect()->route('landing');
            }

            return redirect()->route('pencaker.dashboard');
        }

        if ($user->peran === 'perusahaan') {
            return redirect()->route('perusahaan.dashboard');
        }

        if ($user->peran === 'disnaker') {
            return redirect()->route('disnaker.dashboard');
        }

        return redirect()->route('landing');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $user = Auth::user();
        $previousUrl = url()->previous();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && $user->peran === 'pencaker') {
            if (str_contains($previousUrl, route('landing'))) {
                return redirect()->route('landing');
            }

            return redirect()->route('login');
        }

        return redirect()->route('login');
    }

    public function showForgotForm()
    {
        return view('auth.forgot', [
            'title' => 'Lupa Password / Email'
        ]);
    }

    public function processForgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pengguna,email'
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format :attribute tidak valid.',
            'exists' => ':attribute tidak ditemukan dalam sistem.',
        ], [
            'email' => 'Email'
        ]);

        $token = Str::random(60);

        PasswordReset::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => Hash::make($token), // ✅ FIX: jangan bcrypt langsung random tanpa verify
                'created_at' => now()
            ]
        );

        return redirect()
            ->route('reset.form', $token)
            ->with('success', 'Link reset password berhasil dibuat. Silakan lanjutkan.');
    }

    public function showResetForm($token)
    {
        return view('auth.reset', [
            'title' => 'Reset Password',
            'token' => $token
        ]);
    }

    public function processReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:pengguna,email',
            'password' => 'required|min:8|confirmed'
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format :attribute tidak valid.',
            'exists' => ':attribute tidak ditemukan dalam sistem.',
            'min' => ':attribute minimal :min karakter.',
            'confirmed' => 'Konfirmasi password tidak sesuai.',
        ], [
            'token' => 'Token',
            'email' => 'Email',
            'password' => 'Password',
        ]);

        $reset = PasswordReset::where('email', $request->email)->first();

        if (!$reset) {
            return back()->with('error', 'Link reset tidak valid atau sudah digunakan.');
        }

        // ✅ VERIFY TOKEN (INI YANG HILANG SEBELUMNYA)
        if (!Hash::check($request->token, $reset->token)) {
            return back()->with('error', 'Token reset tidak valid.');
        }

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Akun tidak ditemukan.');
        }

        $user->update([
            'kata_sandi' => Hash::make($request->password)
        ]);

        PasswordReset::where('email', $request->email)->delete();

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }

    public function showForgotEmailForm()
    {
        return view('auth.forgot-email', [
            'title' => 'Lupa Email'
        ]);
    }

    public function processForgotEmail(Request $request)
    {
        $request->validate([
            'identitas' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
        ], [
            'identitas' => 'NIK / NIP / NIB / Nomor HP',
        ]);

        $identitas = $request->identitas;
        $roleGuess = $this->detectRole($identitas);

        // 🔥 INI YANG KAMU LUPA
        session()->flash('detected_role', $roleGuess);

        $user = null;

        if ($roleGuess === 'pencaker') {

            $user = Pengguna::whereHas('profilPencariKerja', function ($q) use ($identitas) {
                $q->where('nik', $identitas)
                    ->orWhere('nomor_hp', $identitas);
            })->first();
        } elseif ($roleGuess === 'perusahaan') {

            $user = Pengguna::whereHas('profilPerusahaan', function ($q) use ($identitas) {
                $q->where('nib', $identitas)
                    ->orWhere('nomor_telepon', $identitas);
            })->first();
        } elseif ($roleGuess === 'disnaker') {

            $user = Pengguna::where('nip', $identitas)->first();
        } else {

            $user = Pengguna::where('nip', $identitas)
                ->orWhereHas('profilPencariKerja', function ($q) use ($identitas) {
                    $q->where('nik', $identitas)
                        ->orWhere('nomor_hp', $identitas);
                })
                ->orWhereHas('profilPerusahaan', function ($q) use ($identitas) {
                    $q->where('nib', $identitas)
                        ->orWhere('nomor_telepon', $identitas);
                })
                ->first();
        }

        if (!$user) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        return redirect()
            ->route('login')
            ->with('success', 'Data ditemukan. Email Anda: ' . $user->email);
    }

    private function detectRole($input)
    {
        $input = trim($input);

        // 1. NIK (Pencaker) = biasanya 16 digit
        if (preg_match('/^\d{16}$/', $input)) {
            return 'pencaker';
        }

        // 2. NIP (Disnaker) = biasanya lebih panjang & sering ada huruf/format ASN
        if (preg_match('/^(NIP)?[0-9]{8,20}$/', $input)) {
            return 'disnaker';
        }

        // 3. NIB (Perusahaan) = 13-20 digit (OSS)
        if (preg_match('/^\d{8,20}$/', $input)) {
            return 'perusahaan';
        }

        // 4. Nomor HP (fallback)
        if (preg_match('/^[0-9]{10,15}$/', $input)) {
            return 'pencaker'; // HP biasanya milik pencaker/perusahaan
        }

        return null;
    }
}
