<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Access - OTP Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display:flex; align-items:center; }
        .otp-card { max-width: 450px; width: 100%; margin: auto; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .otp-input { font-size: 2rem; letter-spacing: 0.8rem; font-weight: bold; text-align: center; border: 2px solid #007bff; border-radius: 8px; }
        .otp-input:focus { border-color: #0056b3; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
        .shield-icon { font-size: 3rem; }
        .countdown { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="card otp-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="shield-icon">🔐</div>
                <h4 class="mt-2 font-weight-bold">Enquiry Page Access</h4>
                <p class="text-muted">Security verification required</p>
            </div>

            @if(isset($error))
                <div class="alert alert-danger text-center">
                    <strong>{{ $error }}</strong>
                </div>
            @endif

            @if(isset($debugOtp) && $debugOtp)
                <div class="alert alert-warning text-center">
                    ⚠️ Mail send nahi hui. Emergency OTP:
                    <h2 class="mt-2" style="letter-spacing:8px;color:#dc3545"><strong>{{ $debugOtp }}</strong></h2>
                    <small>Ye sirf tabhi dikhta hai jab mail fail ho</small>
                </div>
            @elseif(isset($resent) && $resent)
                <div class="alert alert-info text-center">
                    ✉️ Naya OTP bheja gaya hai!
                </div>
            @else
                <div class="alert alert-success text-center">
                    ✉️ OTP bheja gaya hai: <strong>{{ $email }}</strong>
                    <br><small class="text-muted">(Valid for 10 minutes)</small>
                </div>
            @endif

            <form method="POST" action="{{ url('admin/enquiry') }}">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">Enter 6-digit OTP</label>
                    <input
                        type="text"
                        name="enquiry_otp"
                        class="form-control otp-input"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        placeholder="• • • • • •"
                        autofocus
                        required
                    >
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                    ✅ Verify & Access
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ url('admin/enquiry/resend-otp') }}" class="text-muted small">
                    🔄 Resend OTP
                </a>
                &nbsp;|&nbsp;
                <a href="{{ url('admin/dashboard') }}" class="text-muted small">
                    ← Back to Dashboard
                </a>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <span id="timer"></span>
                </small>
            </div>
        </div>
    </div>
</div>

<script>
// Countdown timer
let seconds = 600; // 10 minutes
function updateTimer() {
    let m = Math.floor(seconds / 60);
    let s = seconds % 60;
    document.getElementById('timer').textContent = 
        'OTP expires in: ' + m + ':' + (s < 10 ? '0' : '') + s;
    if (seconds > 0) {
        seconds--;
        setTimeout(updateTimer, 1000);
    } else {
        document.getElementById('timer').textContent = '⚠️ OTP expired. Please resend.';
        document.getElementById('timer').style.color = '#dc3545';
    }
}
updateTimer();
</script>
</body>
</html>
