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

        // Test data
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '9999999999',
            'message' => 'This is a test lead from RSM Multilink',
            'source' => 'Website Test',
        ];

        try {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $settings->api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array_merge($testData, [
                'api_key' => $settings->api_key
            ])));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);
            
            // Save test response
            $settings->test_response = json_encode([
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error,
                'timestamp' => now()->toDateTimeString()
            ]);
            $settings->last_tested_at = now();
            $settings->save();
            
            if ($error) {
                return back()->with('custom_errors', 'CRM API Test Failed: ' . $error);
            }
            
            if ($httpCode >= 200 && $httpCode < 300) {
                return back()->with('custom_success', 'CRM API Test Successful! HTTP Code: ' . $httpCode . ' | Response: ' . $response);
            } else {
                return back()->with('custom_errors', 'CRM API Test Failed! HTTP Code: ' . $httpCode . ' | Response: ' . $response);
            }
            
        } catch (\Exception $e) {
            return back()->with('custom_errors', 'CRM API Test Exception: ' . $e->getMessage());
        }
    }
}
