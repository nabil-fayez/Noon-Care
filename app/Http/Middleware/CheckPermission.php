<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // التحقق من أن المستخدم مصادق عليه
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // التحقق من الصلاحية
        if (!$admin->hasPermission($permission)) {
            // إعادة توجيه إلى dashboard مع رسالة تنبيه
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك الصلاحية الكافية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
