<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = array_keys(config('auth.guards'));
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectBasedOnUserType(Auth::guard($guard)->user());
            }
        }

        return $next($request);
    }

    /**
     * توجيه المستخدم بناءً على نوعه
     */
    protected function redirectBasedOnUserType($user)
    {
        $userType = $this->getUserType($user);
        
        $redirectRoutes = [
            'admin' => 'admin.dashboard',
            'doctor' => 'doctor.dashboard',
            'facility' => 'facility.dashboard',
            'patient' => 'patient.dashboard',
        ];

        $route = $redirectRoutes[$userType] ?? 'home';

        return redirect()->route($route);
    }

    /**
     * تحديد نوع المستخدم
     */
    protected function getUserType($user)
    {
        $modelToTypeMap = [
            \App\Models\Patient::class => 'patient',
            \App\Models\Doctor::class => 'doctor',
            \App\Models\Facility::class => 'facility',
            \App\Models\Admin::class => 'admin',
        ];
        
        $userClass = get_class($user);
        
        return $modelToTypeMap[$userClass] ?? 'user';
    }
}