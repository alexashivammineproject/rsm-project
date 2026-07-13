<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class CustomMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            // Get password from env and handle special characters properly
            $password = env('MAIL_PASSWORD');
            
            // Remove surrounding quotes if present
            $password = trim($password, '"\'');
            
            // Set mail configuration
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => env('MAIL_HOST'),
                'port' => env('MAIL_PORT'),
                'encryption' => env('MAIL_ENCRYPTION'),
                'username' => env('MAIL_USERNAME'),
                'password' => $password,
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ]);
            
            Config::set('mail.from', [
                'address' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME'),
            ]);
            
            // Set stream options for better SSL/TLS handling
            Config::set('mail.stream', [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            
            // Log removed - was causing log spam on every request
        } catch (\Exception $e) {
            \Log::error('CustomMailServiceProvider error: ' . $e->getMessage());
        }
    }
}
