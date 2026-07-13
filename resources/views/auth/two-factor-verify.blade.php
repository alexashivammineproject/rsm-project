@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">🔐 Two-Factor Authentication</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">
                        Open your authenticator app (Google Authenticator / Authy) and enter the 6-digit code.
                    </p>

                    <form method="POST" action="{{ route('2fa.verify.post') }}">
                        @csrf

                        <div class="form-group">
                            <label for="code" class="font-weight-bold">Authentication Code</label>
                            <input 
                                id="code" 
                                type="text" 
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="6"
                                class="form-control form-control-lg text-center {{ $errors->has('code') ? 'is-invalid' : '' }}" 
                                name="code" 
                                placeholder="000000"
                                autocomplete="one-time-code"
                                autofocus
                                required
                            >
                            @if ($errors->has('code'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                            Verify & Login
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted small">← Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#code { font-size: 2rem; letter-spacing: 0.5rem; font-weight: bold; }
</style>
@endsection
