<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EducatorProfileVerified
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
        if (auth()->user()->educatorProfile->status == 'pending') {
            return redirect()->route('educator.dashboard')->with('error', 'Your educator profile is pending approval. You will be notified once the review is complete.');
        }
        if (auth()->user()->educatorProfile->status == 'rejected') {
            return redirect()->route('educator.dashboard')->with('error', 'Your educator profile has been rejected. Please contact admin for assistance.');
        }

        return $next($request);
    }
}
