@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🔐 Setup Two-Factor Authentication (2FA)</h5>
                </div>
                <div class="card-body p-4">

                    <div class="alert alert-info">
                        <strong>Instructions:</strong> Install <strong>Google Authenticator</strong> or <strong>Authy</strong> on your phone, then scan the QR code below.
                    </div>

                    <div class="row align-items-center">
                        {{-- QR Code --}}
                        <div class="col-md-5 text-center mb-3">
                            <p class="font-weight-bold">Step 1: Scan this QR Code</p>
                            <img 
                                src="{{ $qrUrl }}" 
                                alt="QR Code" 
                                class="border p-2"
                                style="width:220px;height:220px"
                                onerror="this.style.display='none'; document.getElementById('qr-fallback').style.display='block';"
                            >
                            <div id="qr-fallback" style="display:none" class="alert alert-warning mt-2">
                                QR image load nahi hua. Neeche manual key use karo.
                            </div>
                        </div>

                        <div class="col-md-7">
                            {{-- Manual Key --}}
                            <p class="font-weight-bold">Or enter this key manually in app:</p>
                            <div class="alert alert-secondary text-center mb-3">
                                <code style="font-size:1.2rem;letter-spacing:3px;word-break:break-all">{{ chunk_split($secret, 4, ' ') }}</code>
                                <br><small class="text-muted">Type: Time based (TOTP)</small>
                            </div>

                            <hr>

                            {{-- Verify Code --}}
                            <p class="font-weight-bold">Step 2: Enter the 6-digit code from app</p>
                            <form method="POST" action="{{ route('2fa.enable') }}">
                                @csrf
                                <div class="input-group">
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        maxlength="6"
                                        class="form-control form-control-lg text-center {{ $errors->has('code') ? 'is-invalid' : '' }}"
                                        name="code"
                                        placeholder="000000"
                                        autocomplete="off"
                                        autofocus
                                        required
                                    >
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            ✅ Enable 2FA
                                        </button>
                                    </div>
                                </div>
                                @if ($errors->has('code'))
                                    <span class="text-danger d-block mt-1"><strong>{{ $errors->first('code') }}</strong></span>
                                @endif
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6>📱 How to add manually in Google Authenticator:</h6>
                    <ol class="mb-0">
                        <li>App open karo → <strong>+</strong> button tap karo</li>
                        <li><strong>"Enter a setup key"</strong> select karo</li>
                        <li>Account name: <code>RSMMultilink</code></li>
                        <li>Key: copy karo upar wala secret key</li>
                        <li>Type: <strong>Time based</strong> select karo</li>
                        <li><strong>Add</strong> tap karo</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
