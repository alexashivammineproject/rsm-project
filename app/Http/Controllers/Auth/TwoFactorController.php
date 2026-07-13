<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA verification form (after login)
     */
    public function showVerifyForm()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.two-factor-verify');
    }

    /**
     * Verify the 2FA code submitted after login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = session('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $user = \App\User::findOrFail($userId);
        $secret = TwoFactorService::decryptSecret($user->two_factor_secret);

        if (!TwoFactorService::verifyCode($secret, $request->code)) {
            return back()->withErrors(['code' => 'Invalid 2FA code. Please try again.']);
        }

        // Code is correct - log the user in
        Auth::loginUsingId($userId, session('2fa_remember', false));
        session()->forget(['2fa_user_id', '2fa_remember']);

        return redirect()->intended('admin/dashboard');
    }

    /**
     * Show 2FA setup page (in admin profile)
     */
    public function showSetup()
    {
        $user = Auth::user();

        if ($user->two_factor_enabled) {
            return view('auth.two-factor-manage', ['enabled' => true]);
        }

        // Generate new secret if not exists
        if (!$user->two_factor_secret) {
            $secret = TwoFactorService::generateSecret();
            $user->two_factor_secret = TwoFactorService::encryptSecret($secret);
            $user->save();
        } else {
            $secret = TwoFactorService::decryptSecret($user->two_factor_secret);
        }

        $qrUrl = TwoFactorService::getQrCodeUrl($user->email, $secret);

        return view('auth.two-factor-setup', compact('secret', 'qrUrl'));
    }

    /**
     * Enable 2FA after user confirms the setup code
     */
    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $user = Auth::user();
        $secret = TwoFactorService::decryptSecret($user->two_factor_secret);

        if (!TwoFactorService::verifyCode($secret, $request->code)) {
            return back()->withErrors(['code' => 'Invalid code. Please scan the QR code again and enter the 6-digit code.']);
        }

        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        return redirect()->route('2fa.setup')->with('success', '✅ Two-Factor Authentication enabled successfully!');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('2fa.setup')->with('success', '2FA has been disabled.');
    }
}
