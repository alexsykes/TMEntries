<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailRateLimit
{
    /**q
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $job, Closure $next)
    {
        Redis::throttle('email-throttle')
            ->block(2)
            ->allow(10)
            ->every(2)
            ->then(function() use($job, $next) {
                $next($job);
            },
                function() use($job) {
                $job->release(30);
                }

            );
    }
}
