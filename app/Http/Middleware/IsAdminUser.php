<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = Auth::user();
            $isAdminUser = $user->isAdminUser;
            if ($isAdminUser) {
                info('AdminUser requested');

                return $next($request);
            } else {
                return redirect('/');
            }
        }

        return redirect('/');
    }
}
