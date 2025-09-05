<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Services\ErrorLogService;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    public function index(Request $request)
    {
        $errorLogs = ErrorLog::with('user')
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->search($request->search);
            })
            ->when($request->has('level'), function ($query) use ($request) {
                return $query->level($request->level);
            })
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->ofUser($request->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = ErrorLogService::getStats();

        return view('admin.error-logs.index', compact('errorLogs', 'stats'));
    }

    public function show(ErrorLog $errorLog)
    {
        $errorLog->load('user');

        return view('admin.error-logs.show', compact('errorLog'));
    }

    public function destroy(ErrorLog $errorLog)
    {
        $errorLog->delete();

        return redirect()->route('admin.error-logs.index')
            ->with('success', 'تم حذف سجل الخطأ بنجاح.');
    }

    public function clearOldLogs()
    {
        $days = 30; // احتفظ بالسجلات لمدة 30 يوم فقط
        $deleted = ErrorLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('admin.error-logs.index')
            ->with('success', "تم حذف {$deleted} سجل خطأ قديم.");
    }
}
