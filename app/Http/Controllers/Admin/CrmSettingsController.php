<?php

namespace App\Http\Controllers\Admin;

use App\CrmSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CrmSettingsController extends Controller
{
    /**
     * Display CRM settings
     */
    public function index()
    {
        $settings = CrmSetting::first();
        return view('admin.crm-settings.index', compact('settings'));
    }

    /**
     * Update CRM settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'api_url' => 'required|url',
            'is_active' => 'boolean',
        ]);

        $settings = CrmSetting::first();
        
        if (!$settings) {
            $settings = new CrmSetting();
        }

        $settings->api_key = $request->api_key;
        $settings->api_url = $request->api_url;
        $settings->is_active = $request->has('is_active') ? true : false;
        $settings->save();

        return back()->with('custom_success', 'CRM settings updated successfully');
    }

    /**
     * Test CRM API connection
     */
    public function test(Request $request)
    {
        $settings = CrmSetting::first();
        
        if (!$settings) {
            return back()->with('custom_errors', 'Please configure CRM settings first');
        }

        // Test data - ensure all required fields
        $testData = [
            'api_key' => $settings->api_key,
            'name' => 'Test User',
            'email' => 'test@rsmmultilink.com',
            'phone' => '9999999999',
            'message' => 'This is a test lead from RSM Multilink CRM Integration',
            'source' => 'Website Test',
            'country_code' => '+91',
        ];

        try {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $settings->api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
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
            
            // Save test response
            $settings->test_response = json_encode([
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error,
                'sent_data' => $testData,
                'timestamp' => now()->toDateTimeString()
            ], JSON_PRETTY_PRINT);
            $settings->last_tested_at = now();
            $settings->save();
            
            // Log for debugging
            \Log::info('CRM Test', [
                'url' => $settings->api_url,
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error,
                'sent_data' => $testData
            ]);
            
            if ($error) {
                return back()->with('custom_errors', 'CRM API Test Failed: ' . $error);
            }
            
            if ($httpCode >= 200 && $httpCode < 300) {
                return back()->with('custom_success', 'CRM API Test Successful! HTTP Code: ' . $httpCode . ' | Response: ' . $response);
            } else {
                return back()->with('custom_errors', 'CRM API Test Failed! HTTP Code: ' . $httpCode . ' | Response: ' . $response . ' | Please check your API key and URL.');
            }
            
        } catch (\Exception $e) {
            \Log::error('CRM Test Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('custom_errors', 'CRM API Test Exception: ' . $e->getMessage());
        }
    }
}
