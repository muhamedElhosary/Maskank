<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenPermissions
{
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        $user = $request->user();
        foreach ($abilities as $ability) {
            if ($user->tokenCan($ability)) {
                 return $next($request);
            }
        }

        return response()->json(['message' => 'Not allowed'], 403);

    }
}