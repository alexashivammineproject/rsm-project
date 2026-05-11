@extends('admin/layouts.app')

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>CRM Integration Settings</h4>
        <p class="text-muted mb-0">Configure CRM API to automatically send form submissions as leads</p>
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('crm-settings.update') }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="api_url">CRM API URL <span class="required">*</span></label>
                        <input type="url" class="form-control" id="api_url" name="api_url" 
                               value="{{ old('api_url', $settings->api_url ?? '') }}" 
                               placeholder="https://leads.knowyourmedi.com/api/webhook/enquiry" required>
                        <small class="form-text text-muted">The webhook URL where leads will be sent</small>
                    </div>

                    <div class="form-group">
                        <label for="api_key">API Key <span class="required">*</span></label>
                        <input type="text" class="form-control" id="api_key" name="api_key" 
                               value="{{ old('api_key', $settings->api_key ?? '') }}" 
                               placeholder="your_api_key_here" required>
                        <small class="form-text text-muted">Your CRM API authentication key</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                   {{ old('is_active', $settings->is_active ?? true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                <strong>Enable CRM Integration</strong>
                            </label>
                        </div>
                        <small class="form-text text-muted">When enabled, all form submissions will be sent to CRM</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                        <a href="{{ route('crm-settings.test') }}" class="btn btn-info" 
                           onclick="return confirm('This will send a test lead to your CRM. Continue?')">
                            <i class="fas fa-vial"></i> Test Connection
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Integration Status</h5>
                            
                            @if($settings && $settings->is_active)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> <strong>Active</strong>
                                    <p class="mb-0 mt-2 small">CRM integration is enabled. All form submissions will be sent as leads.</p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Inactive</strong>
                                    <p class="mb-0 mt-2 small">CRM integration is disabled. Enable it to start sending leads.</p>
                                </div>
                            @endif

                            @if($settings && $settings->last_tested_at)
                                <hr>
                                <p class="mb-1"><strong>Last Tested:</strong></p>
                                <p class="text-muted small">{{ $settings->last_tested_at->format('M d, Y h:i A') }}</p>
                                
                                @if($settings->test_response)
                                    <p class="mb-1"><strong>Test Response:</strong></p>
                                    <pre class="bg-white p-2 small" style="max-height: 200px; overflow-y: auto;">{{ $settings->test_response }}</pre>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Forms Integrated:</h6>
                            <ul class="small mb-0">
                                <li>Contact Form</li>
                                <li>Enquiry Modal</li>
                                <li>Newsletter Subscription</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>How It Works</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-wpforms fa-3x text-primary mb-3"></i>
                    <h6>1. User Submits Form</h6>
                    <p class="small text-muted">Visitor fills out contact form, enquiry modal, or subscribes to newsletter</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-paper-plane fa-3x text-success mb-3"></i>
                    <h6>2. Data Sent to CRM</h6>
                    <p class="small text-muted">Form data is automatically sent to your CRM via API with authentication</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                    <h6>3. Lead Created</h6>
                    <p class="small text-muted">New lead appears in your CRM dashboard for follow-up</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
