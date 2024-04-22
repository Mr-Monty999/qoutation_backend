<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlySupplier
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
        $user = auth()->user();
        if (!$user->supplier) {
            return response()->json([
                "message" => trans("messages.only supplier can do this action")
            ], 403);
        }

        return $next($request);
    }
}
