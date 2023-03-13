<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Codes;

class VerifyCode
{
    private function logoutCode(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) { 
            return redirect('/');
        }
        $userId = $request->user()->id;
        $authCode = Codes::where('user_id', $userId)->first();
        if (!$authCode) {
            return $this->logoutCode($request);
        }
        if (!$authCode->confirmed) {
            return $this->logoutCode($request);
        }
        return $next($request);
    }
}
