<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

            if (in_array((int) $user->usergroup_id, [1, 2, 3, 4], true)) {
                return redirect('/admin/dashboard');
            }

            if ((int) $user->usergroup_id === 5) {
                return redirect('/member/home');
            }

            return redirect()->route('web.home');
        }

        return $next($request);
    }
}
