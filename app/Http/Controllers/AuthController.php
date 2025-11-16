<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Mail\PasswordResetOtpMail;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        if ($r->hasSession()) {
            $r->session()->regenerate();
        }

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

    /* FORGOT PASSWORD (WEB) */
    public function showForgotPassword()
    {
        return view('auth.forgot-password-otp');
    }

    public function forgotPassword(Request $r)
    {
        $data = $r->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found'])->onlyInput('email');
        }

        // Create or update OTP
        $otpRecord = PasswordResetOtp::createOrUpdateOtp($user->email);

        // Send OTP email
        try {
            \Log::info('Attempting to send OTP email to: ' . $user->email . ' with OTP: ' . $otpRecord->otp);
            MailService::sendWithoutSSLVerification($user->email, new PasswordResetOtpMail($otpRecord->otp, $user->email));
            \Log::info('OTP email sent successfully to: ' . $user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset OTP email: ' . $e->getMessage());
            \Log::error('Email error details: ' . $e->getTraceAsString());
            return back()->withErrors(['email' => 'Failed to send OTP email: ' . $e->getMessage()])->onlyInput('email');
        }

        return redirect()->route('password.reset')->with('success', 'OTP code has been sent to your email address.')->with('email', $data['email']);
    }

    /* RESET PASSWORD (WEB) */
    public function showResetPassword(Request $r)
    {
        $email = $r->session()->get('email') ?? $r->old('email');
        return view('auth.reset-password-otp', compact('email'));
    }

    public function resetPassword(Request $r)
    {
        $data = $r->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Verify OTP
        $otpRecord = PasswordResetOtp::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.'])->withInput();
        }

        // Find user
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found'])->withInput();
        }

        // Update password
        $user->password = Hash::make($data['password']);
        $user->save();

        // Mark OTP as used
        $otpRecord->markAsUsed();

        return redirect()->route('login')->with('success', 'Password has been reset successfully. You can now login with your new password.');
    }

    /* API METHODS */
    public function apiRegister(Request $r)
    {
        $data = $r->validate([
            'name'     => ['required','string','min:2','max:120'],
            'email'    => ['required','email', Rule::unique('users','email')],
            'password' => ['required','confirmed', Password::min(6)],
            'role'     => ['required', Rule::in(['muhitaji','mfanyakazi'])],
            'phone'    => ['nullable','regex:/^(0[6-7]\d{8}|255[6-7]\d{8})$/'],
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
        if ($r->hasSession()) {
            $r->session()->regenerate();
        }

        // Create Sanctum token for API
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Akaunti imeundwa kwa mafanikio!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'lat' => $user->lat,
                'lng' => $user->lng,
            ]
        ]);
    }

    public function apiLogin(Request $r)
    {
        $cred = $r->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ]);

        if (Auth::attempt(['email'=>$cred['email'], 'password'=>$cred['password']], $r->boolean('remember'))) {
            if ($r->hasSession()) {
                $r->session()->regenerate();
            }
            $user = Auth::user();
            
            // Create Sanctum token
            $token = $user->createToken('API Token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Umeingia kwa mafanikio!',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone' => $user->phone,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Taarifa si sahihi au akaunti haipo.',
            'errors' => ['email' => 'Taarifa si sahihi au akaunti haipo.']
        ], 401);
    }

    public function apiLogout(Request $r)
    {
        // Revoke current token
        $r->user()->currentAccessToken()->delete();
        
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Umetoka kwa mafanikio!'
        ]);
    }

    public function getuser(Request $r)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Huna ruhusa'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'lat' => $user->lat,
                'lng' => $user->lng,
            ]
        ]);
    }

    /**
     * Get authenticated user with wallet balance
     * Additional endpoint for user profile with wallet info
     */
    public function apiUserProfile(Request $r)
    {
        $user = $r->user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'wallet_balance' => $user->wallet?->balance ?? 0,
            ]
        ]);
    }

    /**
     * Request password reset OTP
     * POST /api/auth/forgot-password
     */
    public function apiForgotPassword(Request $r)
    {
        $data = $r->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found',
                'errors' => ['email' => 'Email not found']
            ], 404);
        }

        // Create or update OTP
        $otpRecord = PasswordResetOtp::createOrUpdateOtp($user->email);

        // Send OTP email
        try {
            \Log::info('API: Attempting to send OTP email to: ' . $user->email . ' with OTP: ' . $otpRecord->otp);
            MailService::sendWithoutSSLVerification($user->email, new PasswordResetOtpMail($otpRecord->otp, $user->email));
            \Log::info('API: OTP email sent successfully to: ' . $user->email);
        } catch (\Exception $e) {
            \Log::error('API: Failed to send password reset OTP email: ' . $e->getMessage());
            \Log::error('API: Email error details: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP email: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP code has been sent to your email address.',
        ]);
    }

    /**
     * Verify OTP code
     * POST /api/auth/verify-otp
     * This endpoint verifies the OTP but doesn't mark it as used yet
     * The OTP will be marked as used only after password is successfully reset
     */
    public function apiVerifyOtp(Request $r)
    {
        $data = $r->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $otpRecord = PasswordResetOtp::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code.',
                'errors' => ['otp' => 'Invalid or expired OTP code.']
            ], 400);
        }

        // Don't mark as used yet - will be marked as used after password reset
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully. You can now reset your password.',
        ]);
    }

    /**
     * Reset password with new password
     * POST /api/auth/reset-password
     */
    public function apiResetPassword(Request $r)
    {
        $data = $r->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Verify OTP again (in case it wasn't verified in previous step)
        $otpRecord = PasswordResetOtp::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code.',
                'errors' => ['otp' => 'Invalid or expired OTP code.']
            ], 400);
        }

        // Find user
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found',
                'errors' => ['email' => 'Email not found']
            ], 404);
        }

        // Update password
        $user->password = Hash::make($data['password']);
        $user->save();

        // Mark OTP as used
        $otpRecord->markAsUsed();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully. You can now login with your new password.',
        ]);
    }
}