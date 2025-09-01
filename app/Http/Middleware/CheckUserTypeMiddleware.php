<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$types
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        // محاولة الحصول على المستخدم من جميع الـ guards المتاحة
        $user = $this->getAuthenticatedUser();
        dd($user);
        if (!$user) {
            return redirect()->route('welcome');
        }

        // الحصول على نوع المستخدم
        $userType = $this->getUserType($user);
        
        if (!in_array($userType, $types)) {
            abort(403, 'غير مصرح بالوصول');
        }

        return $next($request);
    }

    /**
     * الحصول على المستخدم المصادق من أي guard
     */
    private function getAuthenticatedUser()
    {
        $guards = config('auth.guards');
        
        foreach ($guards as $guard => $config) {
            $user = Auth::guard($guard)->user();
            if ($user) {
                return $user;
            }
        }
        
        return null;
    }

    /**
     * تحديد نوع المستخدم ديناميكياً
     */
    private function getUserType($user)
    {
        $userClass = get_class($user);
        
        // تحويل اسم الكلاس إلى نوع مستخدم
        $classParts = explode('\\', $userClass);
        $className = end($classParts);
        
        return strtolower($className);
    }
}