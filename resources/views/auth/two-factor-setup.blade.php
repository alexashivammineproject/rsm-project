@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🔐 Setup Two-Factor Authentication</h5>
                </div>
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col-md-5 text-center">
                            <p class="font-weight-bold">Step 1: Scan QR Code</p>
                            <img src="{{ $qrUrl }}" alt="QR Code" class="img-fluid border p-2" style="max-width:200px">
                            <p class="small text-muted mt-2">Use Google Authenticator or Authy</p>
                        </div>
                        <div class="col-md-7">
                            <p class="font-weight-bold">Or enter secret key manually:</p>
                            <div class="alert alert-secondary">
                                <code style="font-size:1.1rem;letter-spacing:2px">{{ $secret }}</code>
                            </div>

                            <hr>

                            <p class="font-weight-bold">Step 2: Enter verification code</p>
                            <form method="POST" action="{{ route('2fa.enable') }}">
                                @csrf
                                <div class="form-group">
                                    <input 
                                        type="text" 
                                        inputmode="numeric"
                                        maxlength="6"
                                        class="form-control form-control-lg text-center {{ $errors->has('code') ? 'is-invalid' : '' }}" 
                                        name="code" 
                                        placeholder="Enter 6-digit code"
                                        autocomplete="off"
                                        autofocus
                                        required
                                    >
                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback d-block">{{ $errors->first('code') }}</span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-success btn-block">
                                    ✅ Enable 2FA
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
