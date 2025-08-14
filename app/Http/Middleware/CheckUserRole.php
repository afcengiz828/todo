<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Giriş yapan kullanıcıyı al
        $user = Auth::guard('api')->user();

        // 2. Yetki kontrolünü yap
        if (! $user || ! in_array($user->role, $roles)) {
            // 3. Yetkisizse 403 hatası dön
            return response()->json(['error' => 'Unauthorized. You do not have the required role.'], 403);
        }

        // 4. Yetkiliyse isteği bir sonraki adıma geçir
        return $next($request);
    }
}
