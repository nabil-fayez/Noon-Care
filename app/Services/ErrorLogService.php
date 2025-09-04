<?php

namespace App\Services;

use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ErrorLogService
{
    /**
     * تسجيل خطأ جديد
     */
    public static function logError($level, $message, $exception = null, Request $request = null)
    {
        $request = $request ?? request();

        $details = null;
        if ($exception instanceof \Exception) {
            $details = [
                'exception_message' => $exception->getMessage(),
                'exception_code' => $exception->getCode(),
                'exception_trace' => $exception->getTraceAsString(),
            ];
        }

        return ErrorLog::create([
            'level' => $level,
            'message' => $message,
            'details' => $details,
            'file' => $exception ? $exception->getFile() : null,
            'line' => $exception ? $exception->getLine() : null,
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * تسجيل خطأ حرج
     */
    public static function logCritical($message, $exception = null, Request $request = null)
    {
        return self::logError('critical', $message, $exception, $request);
    }

    /**
     * تسجيل خطأ عادي
     */
    public static function logErrorLevel($message, $exception = null, Request $request = null)
    {
        return self::logError('error', $message, $exception, $request);
    }

    /**
     * تسجيل تحذير
     */
    public static function logWarning($message, $exception = null, Request $request = null)
    {
        return self::logError('warning', $message, $exception, $request);
    }

    /**
     * تسجيل معلومات
     */
    public static function logInfo($message, Request $request = null)
    {
        return self::logError('info', $message, null, $request);
    }

    /**
     * الحصول على إحصائيات الأخطاء
     */
    public static function getStats($days = 30)
    {
        return [
            'total' => ErrorLog::where('created_at', '>=', now()->subDays($days))->count(),
            'critical' => ErrorLog::where('level', 'critical')
                ->where('created_at', '>=', now()->subDays($days))
                ->count(),
            'errors' => ErrorLog::where('level', 'error')
                ->where('created_at', '>=', now()->subDays($days))
                ->count(),
            'warnings' => ErrorLog::where('level', 'warning')
                ->where('created_at', '>=', now()->subDays($days))
                ->count(),
            'recent_errors' => ErrorLog::with('user')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}