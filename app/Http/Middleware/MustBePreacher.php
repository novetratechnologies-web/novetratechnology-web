<?php

namespace App\Http\Middleware;

use Closure;

class MustBePreacher
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
        if(\Auth::user()->usergroup_id == 6)
        {
            return $next($request);
        }

        if(\Auth::user()->usergroup_id == 3)
        {
            return redirect('/admin/dashboard');
        }
      
        if(in_array((int) \Auth::user()->usergroup_id, [1, 2, 3, 4], true))
        {
            return redirect('/admin/dashboard');
        }

        
       abort(404);
    }
}
