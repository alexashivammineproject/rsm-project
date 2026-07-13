<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DB;

class EnquiryOtpMiddleware
{
    // Email jis par OTP jayega
    const PROTECTED_EMAIL = 'kumarshivam827@gmail.com';

    public function handle(Request $request, Closure $next)
    {
        // Must be logged in
        if (!Auth::check()) {
            return redirect('login');
        }

        $sessionKey = 'enquiry_otp_verified';
        $sessionExpiry = 'enquiry_otp_expiry';

        // Check if already verified in this session (valid for 30 mins)
        if (session($sessionKey) === true && session($sessionExpiry) > now()->timestamp) {
            return $next($request);
        }

        // If OTP form submitted
        if ($request->isMethod('post') && $request->has('enquiry_otp')) {
            return $this->verifyOtp($request, $next);
        }

        // Generate and send new OTP
        $sent = $this->sendOtp($request);

        // If mail failed completely, show OTP on screen (emergency fallback)
        $debugOtp = session('enquiry_otp_debug');

        return response()->view('admin.enquiries.otp-verify', [
            'email'    => self::maskEmail(self::PROTECTED_EMAIL),
            'mailSent' => $sent,
            'debugOtp' => $debugOtp, // only shown if mail failed
        ]);
    }

    private function verifyOtp(Request $request, Closure $next)
    {
        $otp = trim($request->input('enquiry_otp'));

        $record = DB::table('email_otps')
            ->where('email', self::PROTECTED_EMAIL)
            ->where('otp', $otp)
            ->where('purpose', 'enquiry')
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            // Check if OTP exists but expired
            $expired = DB::table('email_otps')
                ->where('email', self::PROTECTED_EMAIL)
                ->where('otp', $otp)
                ->where('purpose', 'enquiry')
                ->where('used', false)
                ->first();

            $message = $expired ? 'OTP expired hai. Naya OTP bheja ja raha hai.' : 'Invalid OTP. Please try again.';

            if ($expired) {
                $sent = $this->sendOtp(request());
                $debugOtp = session('enquiry_otp_debug');
            }

            return response()->view('admin.enquiries.otp-verify', [
                'email'    => self::maskEmail(self::PROTECTED_EMAIL),
                'error'    => $message,
                'resent'   => (bool)$expired,
                'mailSent' => $sent ?? true,
                'debugOtp' => $debugOtp ?? null,
            ]);
        }

        // Mark as used
        DB::table('email_otps')->where('id', $record->id)->update(['used' => true]);

        // Set session verified for 30 minutes
        session([
            'enquiry_otp_verified' => true,
            'enquiry_otp_expiry'   => now()->addMinutes(30)->timestamp,
        ]);

        return redirect()->route('enquiry.index');
    }

    private function sendOtp(Request $request)
    {
        // Delete old OTPs for this email+purpose
        DB::table('email_otps')
            ->where('email', self::PROTECTED_EMAIL)
            ->where('purpose', 'enquiry')
            ->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save to DB
        DB::table('email_otps')->insert([
            'email'      => self::PROTECTED_EMAIL,
            'otp'        => $otp,
            'purpose'    => 'enquiry',
            'used'       => false,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $htmlBody = "
            <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;padding:20px;border:1px solid #ddd;border-radius:8px'>
                <h2 style='color:#2c3e50'>🔐 RSM Admin - Enquiry Access OTP</h2>
                <p>Aapne Enquiry page access karne ki request ki hai.</p>
                <div style='background:#f8f9fa;border:2px dashed #007bff;padding:20px;text-align:center;margin:20px 0;border-radius:6px'>
                    <h1 style='color:#007bff;letter-spacing:8px;margin:0;font-size:2.5rem'>{$otp}</h1>
                </div>
                <p style='color:#666'>Ye OTP <strong>10 minutes</strong> ke liye valid hai.</p>
                <p style='color:#999;font-size:12px'>Agar aapne ye request nahi ki, please ignore karein.</p>
                <hr>
                <p style='color:#999;font-size:11px'>RSMMultilink Admin Security</p>
            </div>
        ";

        $sent = false;

        // Method 1: Laravel Mail (SMTP)
        try {
            Mail::send([], [], function ($message) use ($otp, $htmlBody) {
                $message->to(self::PROTECTED_EMAIL)
                    ->subject('RSM Admin - Enquiry Page OTP: ' . $otp)
                    ->html($htmlBody);
            });
            $sent = true;
            \Log::info('EnquiryOTP: Sent via SMTP to ' . self::PROTECTED_EMAIL);
        } catch (\Exception $e) {
            \Log::warning('EnquiryOTP SMTP failed: ' . $e->getMessage() . ' - Trying sendmail fallback');
        }

        // Method 2: PHP mail() fallback if SMTP failed
        if (!$sent) {
            try {
                $to      = self::PROTECTED_EMAIL;
                $subject = 'RSM Admin - Enquiry Page OTP: ' . $otp;
                $headers = implode("\r\n", [
                    'From: RSMMultilink Admin <noreply@rsmmultilink.com>',
                    'Reply-To: noreply@rsmmultilink.com',
                    'MIME-Version: 1.0',
                    'Content-Type: text/html; charset=UTF-8',
                    'X-Mailer: PHP/' . phpversion(),
                ]);

                $result = mail($to, $subject, $htmlBody, $headers);

                if ($result) {
                    $sent = true;
                    \Log::info('EnquiryOTP: Sent via PHP mail() to ' . self::PROTECTED_EMAIL);
                } else {
                    \Log::error('EnquiryOTP: PHP mail() also failed');
                }
            } catch (\Exception $e) {
                \Log::error('EnquiryOTP PHP mail() exception: ' . $e->getMessage());
            }
        }

        // Store OTP in session too as emergency fallback (show on screen if mail fails)
        session(['enquiry_otp_debug' => $sent ? null : $otp]);

        return $sent;
    }

    private static function maskEmail(string $email): string
    {
        [$user, $domain] = explode('@', $email);
        $masked = substr($user, 0, 2) . str_repeat('*', max(strlen($user) - 4, 2)) . substr($user, -2);
        return $masked . '@' . $domain;
    }
}
