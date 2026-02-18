<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClubUser
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
            $isClubUser = $user->isClubUser;
            if ($isClubUser) {
                info('Club User requested');

                return $next($request);
            } else {
                return redirect('/');
            }
        }

        return redirect('/');
    }
}
