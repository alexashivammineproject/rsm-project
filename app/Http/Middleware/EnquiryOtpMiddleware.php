<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TwoFactorService;

/**
 * Enquiry page 2FA protection
 * User ko Google Authenticator se verify karna padega enquiry page access karne ke liye
 */
class EnquiryOtpMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Agar 2FA enabled nahi hai to setup pe bhejo
        if (!$user->two_factor_enabled || !$user->two_factor_secret) {
            return redirect()->route('2fa.setup')
                ->with('warning', '⚠️ Enquiry page access karne ke liye pehle 2FA setup karo.');
        }

        // Already verified in session (30 min valid)
        if (session('enquiry_2fa_verified') === true &&
            session('enquiry_2fa_expiry') > now()->timestamp) {
            return $next($request);
        }

        // 2FA code submitted - GET or POST dono handle karo
        if ($request->has('enquiry_2fa_code')) {
            return $this->verify2FA($request, $next, $user);
        }

        return response()->view('admin.enquiries.two-factor-verify');
    }

    private function verify2FA(Request $request, Closure $next, $user)
    {
        $code   = trim($request->input('enquiry_2fa_code'));
        $secret = TwoFactorService::decryptSecret($user->two_factor_secret);

        if (!TwoFactorService::verifyCode($secret, $code)) {
            return response()->view('admin.enquiries.two-factor-verify', [
                'error' => '❌ Invalid code. Please try again.',
            ]);
        }

        // Verified - set session for 30 minutes
        session([
            'enquiry_2fa_verified' => true,
            'enquiry_2fa_expiry'   => now()->addMinutes(30)->timestamp,
        ]);

        return redirect()->route('enquiry.index');
    }
}
