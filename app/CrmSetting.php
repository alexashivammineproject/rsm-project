<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmSetting extends Model
{
    protected $fillable = [
        'api_key',
        'api_url',
        'is_active',
        'test_response',
        'last_tested_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_tested_at' => 'datetime',
    ];

    /**
     * Get the active CRM settings
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Send lead to CRM
     */
    public static function sendLead($data)
    {
        $settings = self::getActive();
        
        if (!$settings) {
            \Log::warning('CRM: No active CRM settings found');
            return false;
        }

        try {
            // Prepare data with API key
            $postData = array_merge($data, [
                'api_key' => $settings->api_key
            ]);
            
            // Ensure required fields
            if (!isset($postData['name'])) {
                $postData['name'] = 'Unknown';
            }
            if (!isset($postData['phone'])) {
                $postData['phone'] = '';
            }
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $settings->api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);
            
            if ($error) {
                \Log::error('CRM API Error: ' . $error);
                return false;
            }
            
            \Log::info('CRM API Response', [
                'http_code' => $httpCode,
                'response' => $response,
                'sent_data' => $postData
            ]);
            
            return $httpCode >= 200 && $httpCode < 300;
            
        } catch (\Exception $e) {
            \Log::error('CRM Exception: ' . $e->getMessage());
            return false;
        }
    }
}
