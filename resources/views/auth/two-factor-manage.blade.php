@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">🔐 Two-Factor Authentication - Active</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-success">
                        ✅ Your account is protected with Two-Factor Authentication.
                    </div>

                    <p>To disable 2FA, enter your current password:</p>

                    <form method="POST" action="{{ route('2fa.disable') }}">
                        @csrf
                        @method('DELETE')
                        <div class="form-group">
                            <label>Current Password</label>
                            <input 
                                type="password" 
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                name="password" 
                                required
                            >
                            @if($errors->has('password'))
                                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to disable 2FA?')">
                            Disable 2FA
                        </button>
                        <a href="{{ url('admin/dashboard') }}" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
