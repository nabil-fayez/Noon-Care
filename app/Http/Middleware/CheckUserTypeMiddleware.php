<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CheckUserTypeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = Auth::user();
        
        if (!$user) {
            $user = $this->getUserFromAnyGuard();
        }
        
        if (!$user) {
            return $this->redirectToLoginWithIntendedUrl($request, $types);
        }

        $userType = $this->getUserType($user);
        
        if (!in_array($userType, $types)) {
            abort(403, 'غير مصرح بالوصول');
        }

        $this->setAppropriateGuard($user);

        return $next($request);
    }

    /**
     * توجيه المستخدم لصفحة تسجيل الدخول مع حفظ URL الحالي
     */
    private function redirectToLoginWithIntendedUrl(Request $request, array $allowedTypes)
    {
        // حفظ المسار الحالي للعودة إليه بعد التسجيل
        if (!$request->session()->has('url.intended')) {
            session(['url.intended' => $request->fullUrl()]);
        }

        // تحديد أفضل صفحة تسجيل دخول
        $loginRoute = $this->determineBestLoginRoute($request, $allowedTypes);

        return redirect()->route($loginRoute);
    }

    /**
     * تحديد أفضل route لتسجيل الدخول
     */
    private function determineBestLoginRoute(Request $request, array $allowedTypes): string
    {
        // المحاولة الأولى: من خلال المسار
        $pathBasedRoute = $this->getLoginRouteFromPath($request->path());
        if ($pathBasedRoute && in_array($this->getTypeFromRoute($pathBasedRoute), $allowedTypes)) {
            return $pathBasedRoute;
        }

        // المحاولة الثانية: من خلال الأنواع المسموحة
        if (count($allowedTypes) === 1) {
            return $this->getLoginRouteForType($allowedTypes[0]);
        }

        // المحاولة الثالثة: من خلال subdomain إن وجد
        $subdomain = $this->getSubdomain($request);
        if ($subdomain) {
            $subdomainRoute = $this->getLoginRouteFromSubdomain($subdomain);
            if ($subdomainRoute && in_array($this->getTypeFromRoute($subdomainRoute), $allowedTypes)) {
                return $subdomainRoute;
            }
        }

        return 'login'; // صفحة تسجيل دخول عامة
    }

    /**
     * الحصول على route التسجيل من المسار
     */
    private function getLoginRouteFromPath(string $path): ?string
    {
        $pathMappings = [
            'admin' => 'admin.login',
            'doctor' => 'doctor.login',
            'facility' => 'facility.login',
            'patient' => 'patient.login',
        ];

        foreach ($pathMappings as $segment => $route) {
            if (str_starts_with($path, $segment . '/')) {
                return $route;
            }
        }

        return null;
    }

    /**
     * الحصول على نوع المستخدم من route التسجيل
     */
    private function getTypeFromRoute(string $route): string
    {
        $routeMappings = [
            'admin.login' => 'admin',
            'doctor.login' => 'doctor',
            'facility.login' => 'facility',
            'patient.login' => 'patient',
        ];

        return $routeMappings[$route] ?? 'unknown';
    }

    /**
     * الحصول على subdomain من Request
     */
    private function getSubdomain(Request $request): ?string
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        
        if (count($parts) > 2) {
            return $parts[0];
        }
        
        return null;
    }

    /**
     * الحصول على route التسجيل من subdomain
     */
    private function getLoginRouteFromSubdomain(string $subdomain): ?string
    {
        $subdomainMappings = [
            'admin' => 'admin.login',
            'doctor' => 'doctor.login',
            'clinic' => 'facility.login',
            'patient' => 'patient.login',
            'user' => 'patient.login',
        ];

        return $subdomainMappings[$subdomain] ?? null;
    }

    /**
     * باقي الدوال تبقى كما هي...
     */
    private function getUserFromAnyGuard()
    {
        $guards = array_keys(config('auth.guards'));
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }
        
        return null;
    }

    private function getUserType($user)
    {
        $modelToTypeMap = [
            \App\Models\Patient::class => 'patient',
            \App\Models\Doctor::class => 'doctor',
            \App\Models\Facility::class => 'facility',
            \App\Models\Admin::class => 'admin',
        ];
        
        return $modelToTypeMap[get_class($user)] ?? 'unknown';
    }

    private function setAppropriateGuard($user)
    {
        $typeToGuardMap = [
            'patient' => 'patient',
            'doctor' => 'doctor',
            'facility' => 'facility',
            'admin' => 'admin',
        ];
        
        $userType = $this->getUserType($user);
        if (isset($typeToGuardMap[$userType])) {
            Auth::shouldUse($typeToGuardMap[$userType]);
        }
    }

    private function getLoginRouteForType(string $userType): string
    {
        $loginRoutes = [
            'patient' => 'patient.login',
            'doctor' => 'doctor.login',
            'facility' => 'facility.login',
            'admin' => 'admin.login',
        ];

        return $loginRoutes[$userType] ?? 'login';
    }
}