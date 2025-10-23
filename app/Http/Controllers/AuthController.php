<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /* REGISTER */
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $r)
    {
        $data = $r->validate([
            'name'     => ['required','string','min:2','max:120'],
            'email'    => ['required','email', Rule::unique('users','email')],
            'password' => ['required','confirmed', Password::min(6)],
            'role'     => ['required', Rule::in(['muhitaji','mfanyakazi'])],
            'phone'    => ['nullable','regex:/^(0[6-7]\d{8}|255[6-7]\d{8})$/'],
            // ✅ Enforce valid geo ranges
            'lat'      => ['nullable','numeric','between:-90,90'],
            'lng'      => ['nullable','numeric','between:-180,180'],
        ],[
            'phone.regex' => 'Weka 06/07xxxxxxxx au 2556/2557xxxxxxxx.',
            'lat.between' => 'Lat lazima iwe kati ya -90 na 90.',
            'lng.between' => 'Lng lazima iwe kati ya -180 na 180.',
        ]);

        // Sanitize & round (ikizidi range, kuwa null)
        $lat = $r->filled('lat') ? (float) $r->input('lat') : null;
        $lng = $r->filled('lng') ? (float) $r->input('lng') : null;

        if ($lat !== null && ($lat < -90 || $lat > 90))   { $lat = null; }
        if ($lng !== null && ($lng < -180 || $lng > 180)) { $lng = null; }

        if ($lat !== null) { $lat = round($lat, 6); }
        if ($lng !== null) { $lng = round($lng, 6); }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'phone'    => $data['phone'] ?? null,
            'lat'      => $lat,
            'lng'      => $lng,
        ]);

        Auth::login($user);
        $r->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /* LOGIN */
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        $cred = $r->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ]);

        if (Auth::attempt(['email'=>$cred['email'], 'password'=>$cred['password']], $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email'=>'Taarifa si sahihi au akaunti haipo.'])->onlyInput('email');
    }

    /* LOGOUT */
    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('home');
    }
}
