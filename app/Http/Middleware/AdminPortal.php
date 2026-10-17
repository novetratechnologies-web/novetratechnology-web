<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminPortal
{
    /** Only administrative staff accounts may enter the admin application. */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && in_array((int) $user->usergroup_id, [1, 2, 3, 4], true)) {
            return $next($request);
        }

        if ($user && (int) $user->usergroup_id === 5) {
            return redirect('/member/home');
        }

        abort(403, 'This area is for church administration staff.');
    }
}
