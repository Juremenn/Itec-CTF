<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.index');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Allow login by either username or email
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptData = [
            $loginField => $credentials['login'],
            'password'  => $credentials['password'],
        ];

        if (Auth::attempt($attemptData, $remember)) {
            $request->session()->regenerate();

            // If administrator logs in, automatically redirect to stealth control center
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.index'))->with('success', 'Selamat datang di Control Center Administrator.');
            }

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'login' => 'Kredensial tidak valid. Silakan periksa username dan password Anda.',
        ])->onlyInput('login');
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $ip = $request->ip();

        // Retrieve or generate a persistent device token
        $deviceToken = $request->cookie('itec_device_id');
        if (empty($deviceToken)) {
            $deviceToken = (string) Str::uuid();
        }

        // Limit registration: maximum 3 accounts per IP and maximum 3 accounts per device
        $ipCount = User::where('registration_ip', $ip)->count();
        $deviceCount = User::where('device_fingerprint', $deviceToken)->count();

        if ($ipCount >= 3 || $deviceCount >= 3) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'register' => 'Batas pendaftaran tercapai: Maksimal 3 akun per alamat IP atau perangkat (Maximum 3 accounts per IP/device limit reached).',
                ]);
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'username.regex' => 'Username hanya boleh terdiri dari huruf, angka, dan garis bawah (_).',
            'username.unique' => 'Username ini sudah terdaftar. Silakan pilih username lain.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $user = User::create([
            'name'                => $validated['name'],
            'username'            => strtolower($validated['username']),
            'email'               => strtolower($validated['email']),
            'password'            => Hash::make($validated['password']),
            'role'                => 'participant',
            'registration_ip'     => $ip,
            'device_fingerprint'  => $deviceToken,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->withCookie(cookie()->forever('itec_device_id', $deviceToken))
            ->with('success', 'Pendaftaran berhasil! Selamat datang di ITEC CyberLab, ' . $user->name . '.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar (signed out).');
    }
}
