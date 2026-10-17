<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        return $request->is('admin', 'admin/*', 'nova-api', 'nova-api/*')
            ? route('admin.login')
            : route('web.guest.login');
    }
}
