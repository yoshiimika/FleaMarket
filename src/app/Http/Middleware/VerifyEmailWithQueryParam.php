<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailWithQueryParam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->query('page') === 'mylist') {
            if (!Auth::check()) {
                $request->merge(['auth_skipped' => true]);
            } elseif (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
        }
        return $next($request);
    }
}
