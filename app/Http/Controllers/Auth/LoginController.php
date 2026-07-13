<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * After credentials are verified - check 2FA
     */
    public function authenticated(Request $request, $user)
    {
        // Check if account is active
        if ($user->is_active === 0) {
            auth()->logout();
            return back()->withErrors(['email' => 'Your account is not active.']);
        }

        // Check if 2FA is enabled for this user
        if ($user->two_factor_enabled && $user->two_factor_secret) {
            // Log out temporarily, store user ID in session for 2FA step
            Auth::logout();
            session([
                '2fa_user_id' => $user->id,
                '2fa_remember' => $request->boolean('remember'),
            ]);
            return redirect()->route('2fa.verify');
        }

        return redirect()->intended($this->redirectPath());
    }
}
