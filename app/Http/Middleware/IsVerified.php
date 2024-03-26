<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Session;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (User::isVerified(Session::get('id'))) {
            return $next($request);
        } else {
            return redirect('verification');
        }

    }
}
