<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Access - 2FA Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a237e 0%, #283593 100%); min-height: 100vh; display:flex; align-items:center; }
        .card { border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border:none; }
        .otp-input { font-size: 2.2rem; letter-spacing: 0.8rem; font-weight: bold; text-align: center; border: 2px solid #1976d2; border-radius: 8px; padding: 15px; }
        .otp-input:focus { border-color: #0d47a1; box-shadow: 0 0 0 0.2rem rgba(25,118,210,.3); outline:none; }
        .shield { font-size: 4rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-5">

                    <div class="text-center mb-4">
                        <div class="shield">🔐</div>
                        <h4 class="mt-3 font-weight-bold text-dark">Enquiry Page Access</h4>
                        <p class="text-muted">Google Authenticator se 6-digit code enter karo</p>
                    </div>

                    @if(isset($error))
                        <div class="alert alert-danger text-center">
                            <strong>{{ $error }}</strong>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning text-center">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('admin/enquiry') }}">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Authentication Code</label>
                            <input
                                type="text"
                                name="enquiry_2fa_code"
                                class="form-control otp-input"
                                maxlength="6"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                placeholder="000000"
                                autocomplete="one-time-code"
                                autofocus
                                required
                            >
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                            ✅ Verify & Access Enquiries
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ url('admin/dashboard') }}" class="text-muted small">
                            ← Back to Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
