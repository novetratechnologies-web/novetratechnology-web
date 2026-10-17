<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['showLoginForm', 'login']);
        $this->middleware(['auth', 'adminportal'])->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with('userprofile')->where('email', $credentials['email'])->first();

        if (
            !$user ||
            !in_array((int) $user->usergroup_id, [1, 2, 3, 4], true) ||
            !Hash::check($credentials['password'], $user->password) ||
            in_array(optional($user->userprofile)->status, ['inactive', 'removed', 'exit'], true) ||
            ($user->church_id && !Church::IsActive($user->church_id)->exists())
        ) {
            return back()->withErrors([
                'email' => 'These credentials are not authorized for the administration portal.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $user->updateQuietly(['last_login_at' => now()]);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.home');
    }
}
