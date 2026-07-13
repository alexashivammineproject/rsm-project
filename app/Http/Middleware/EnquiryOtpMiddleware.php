<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class EnquiryOtpMiddleware
{
    // Email jis par OTP jayega
    const PROTECTED_EMAIL = 'kumarshivam827@gmail.com';
    const FROM_EMAIL      = 'noreply@rsmmultilink.com';
    const FROM_NAME       = 'RSMMultilink Admin';

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Already verified in session (30 min valid)
        if (session('enquiry_otp_verified') === true &&
            session('enquiry_otp_expiry') > now()->timestamp) {
            return $next($request);
        }

        // OTP form submitted
        if ($request->isMethod('post') && $request->has('enquiry_otp')) {
            return $this->verifyOtp($request, $next);
        }

        // Send new OTP
        $this->sendOtp();

        return response()->view('admin.enquiries.otp-verify', [
            'email' => self::maskEmail(self::PROTECTED_EMAIL),
        ]);
    }

    private function verifyOtp(Request $request, Closure $next)
    {
        $otp = trim($request->input('enquiry_otp'));

        // Valid OTP check
        $record = DB::table('email_otps')
            ->where('email', self::PROTECTED_EMAIL)
            ->where('otp', $otp)
            ->where('purpose', 'enquiry')
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            // Check expired
            $expired = DB::table('email_otps')
                ->where('email', self::PROTECTED_EMAIL)
                ->where('purpose', 'enquiry')
                ->where('used', false)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($expired && $expired->otp === $otp) {
                // Correct OTP but expired - resend
                $this->sendOtp();
                return response()->view('admin.enquiries.otp-verify', [
                    'email' => self::maskEmail(self::PROTECTED_EMAIL),
                    'error' => '⏰ OTP expired tha. Naya OTP bheja gaya hai!',
                    'resent' => true,
                ]);
            }

            return response()->view('admin.enquiries.otp-verify', [
                'email' => self::maskEmail(self::PROTECTED_EMAIL),
                'error' => '❌ Invalid OTP. Please try again.',
            ]);
        }

        // Mark used
        DB::table('email_otps')->where('id', $record->id)->update(['used' => true]);

        // Set verified session for 30 mins
        session([
            'enquiry_otp_verified' => true,
            'enquiry_otp_expiry'   => now()->addMinutes(30)->timestamp,
        ]);

        return redirect()->route('enquiry.index');
    }

    private function sendOtp(): bool
    {
        // Delete old unused OTPs
        DB::table('email_otps')
            ->where('email', self::PROTECTED_EMAIL)
            ->where('purpose', 'enquiry')
            ->delete();

        // Generate OTP
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

        $subject = 'RSM Admin - Enquiry Access OTP: ' . $otp;
        $body    = $this->buildEmailHtml($otp);
        $headers = "From: " . self::FROM_NAME . " <" . self::FROM_EMAIL . ">\r\n"
                 . "Reply-To: " . self::FROM_EMAIL . "\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-Type: text/html; charset=UTF-8\r\n"
                 . "X-Mailer: PHP/" . phpversion();

        // PRIMARY: PHP mail() - no SMTP needed, uses server sendmail
        $sent = mail(self::PROTECTED_EMAIL, $subject, $body, $headers);

        if ($sent) {
            \Log::info('EnquiryOTP sent via PHP mail() to ' . self::PROTECTED_EMAIL);
            return true;
        }

        \Log::warning('EnquiryOTP PHP mail() failed, trying SMTP...');

        // FALLBACK: Laravel SMTP
        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($msg) use ($subject, $body) {
                $msg->to(self::PROTECTED_EMAIL)
                    ->from(self::FROM_EMAIL, self::FROM_NAME)
                    ->subject($subject)
                    ->html($body);
            });
            \Log::info('EnquiryOTP sent via SMTP to ' . self::PROTECTED_EMAIL);
            return true;
        } catch (\Exception $e) {
            \Log::error('EnquiryOTP SMTP also failed: ' . $e->getMessage());
            return false;
        }
    }

    private function buildEmailHtml(string $otp): string
    {
        return "<!DOCTYPE html>
<html>
<body style='margin:0;padding:0;background:#f5f5f5;font-family:Arial,sans-serif'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr><td align='center' style='padding:40px 20px'>
<table width='500' cellpadding='0' cellspacing='0' style='background:#fff;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.1)'>
<tr><td style='background:#1a237e;padding:25px;text-align:center;border-radius:10px 10px 0 0'>
    <h2 style='color:#fff;margin:0'>🔐 RSMMultilink Admin</h2>
    <p style='color:#90caf9;margin:5px 0 0'>Enquiry Page Access OTP</p>
</td></tr>
<tr><td style='padding:35px'>
    <p style='color:#333;font-size:16px'>Aapne Admin Enquiry page access karne ki request ki hai.</p>
    <div style='background:#e3f2fd;border:2px solid #1976d2;border-radius:8px;padding:25px;text-align:center;margin:25px 0'>
        <p style='color:#666;margin:0 0 10px;font-size:14px'>Your One-Time Password</p>
        <h1 style='color:#1976d2;font-size:48px;letter-spacing:12px;margin:0;font-weight:900'>{$otp}</h1>
        <p style='color:#999;margin:10px 0 0;font-size:13px'>Valid for 10 minutes only</p>
    </div>
    <p style='color:#666;font-size:14px'>Agar aapne ye request nahi ki, please ignore karein aur apna password change karein.</p>
</td></tr>
<tr><td style='background:#f5f5f5;padding:15px;text-align:center;border-radius:0 0 10px 10px'>
    <p style='color:#999;font-size:12px;margin:0'>RSMMultilink Admin Security System</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>";
    }

    private static function maskEmail(string $email): string
    {
        [$user, $domain] = explode('@', $email);
        $masked = substr($user, 0, 2) . str_repeat('*', max(strlen($user) - 4, 2)) . substr($user, -2);
        return $masked . '@' . $domain;
    }
}
