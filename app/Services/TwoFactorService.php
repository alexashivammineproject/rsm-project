<?php

namespace App\Services;

/**
 * TOTP (Time-based One-Time Password) Service
 * Compatible with Google Authenticator, Authy, Microsoft Authenticator
 * Pure PHP - no external package needed
 */
class TwoFactorService
{
    private const DIGITS = 6;
    private const PERIOD = 30;
    private const ALGORITHM = 'sha1';

    /**
     * Generate a random Base32 secret key
     */
    public static function generateSecret(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $secret;
    }

    /**
     * Verify a TOTP code
     */
    public static function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        $code = trim($code);
        if (!preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $timestamp = floor(time() / self::PERIOD);

        for ($i = -$window; $i <= $window; $i++) {
            if (self::generateCode($secret, $timestamp + $i) === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate TOTP code for a given timestamp
     */
    public static function generateCode(string $secret, int $timestamp = null): string
    {
        if ($timestamp === null) {
            $timestamp = floor(time() / self::PERIOD);
        }

        $secretBytes = self::base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timestamp);
        $hash = hash_hmac(self::ALGORITHM, $time, $secretBytes, true);
        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
        $code = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % (10 ** self::DIGITS);

        return str_pad((string)$code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * Generate QR code URL for Google Authenticator
     * Uses a reliable QR API
     */
    public static function getQrCodeUrl(string $email, string $secret, string $issuer = 'RSMMultilink'): string
    {
        $label = rawurlencode($issuer . ':' . $email);
        $params = http_build_query([
            'secret'    => $secret,
            'issuer'    => $issuer,
            'algorithm' => 'SHA1',
            'digits'    => self::DIGITS,
            'period'    => self::PERIOD,
        ]);

        $otpauth = "otpauth://totp/{$label}?{$params}";

        // Use QR Server API (more reliable than Google Charts)
        return 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . rawurlencode($otpauth);
    }

    /**
     * Base32 decode
     */
    private static function base32Decode(string $input): string
    {
        $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper($input);
        $output = '';
        $buffer = 0;
        $bitsLeft = 0;

        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            if ($char === '=') break;
            $value = strpos($base32Chars, $char);
            if ($value === false) continue;
            $buffer = ($buffer << 5) | $value;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $output .= chr(($buffer >> ($bitsLeft - 8)) & 0xFF);
                $bitsLeft -= 8;
            }
        }

        return $output;
    }

    /**
     * Encrypt secret for database storage
     */
    public static function encryptSecret(string $secret): string
    {
        return encrypt($secret);
    }

    /**
     * Decrypt secret from database
     */
    public static function decryptSecret(string $encryptedSecret): string
    {
        return decrypt($encryptedSecret);
    }
}
