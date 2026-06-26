<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuditLogService $auditLog) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (empty($request->email)) {
            return back()->with('error', 'Email tidak boleh kosong.')->withInput();
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Format email tidak valid.')->withInput();
        }

        if (empty($request->password)) {
            return back()->with('error', 'Password tidak boleh kosong.')->withInput();
        }

        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->auditLog->catat('Auth', 'login', 'User berhasil login ke sistem');
            
            if (Auth::user()->role === 'owner') {
                return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai admin.');
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil.');
        }

        return back()->with('error', 'Email atau password tidak valid.')->withInput();
    }

    public function logout(Request $request)
    {
        $this->auditLog->catat('Auth', 'logout', 'User logout dari sistem');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    public function profil()
    {
        return view('auth.profil', ['user' => Auth::user()]);
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = $request->only('name', 'email');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $lama = $user->toArray();
        $user->update($data);
        
        $this->auditLog->catat('Auth', 'update', 'User memperbarui profil pribadi', $lama, $user->fresh()->toArray());

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
