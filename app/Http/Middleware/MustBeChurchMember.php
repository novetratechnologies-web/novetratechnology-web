<?php

namespace App\Http\Middleware;

use Closure;

class MustBeChurchMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array((int) \Auth::user()->usergroup_id, [1, 2, 3, 4], true)) {

            return redirect('/admin/dashboard');
        }

        if (\Auth::user()->usergroup_id == 5) {
            return $next($request);
        }



        abort(403);
    }
}
