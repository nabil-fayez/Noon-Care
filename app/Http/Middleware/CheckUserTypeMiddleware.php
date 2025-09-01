<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('welcome');
        }
        $userType = $this->getUserType($user);
        if (!in_array($userType, $types)) {
            abort(403, 'غير مصرح بالوصول');
        }
        return $next($request);
    }

    protected function getUserType($user)
    {
        if ($user instanceof \App\Models\Patient) {
            return 'patient';
        } elseif ($user instanceof \App\Models\Doctor) {
            return 'doctor';
        } elseif ($user instanceof \App\Models\Facility) {
            return 'facility';
        } elseif ($user instanceof \App\Models\Admin) {
            return 'admin';
        }

        return false;
    }
}
