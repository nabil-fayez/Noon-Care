<!-- resources/views/admin/error-logs/index.blade.php -->
@extends('layouts.admin')

@section('title', 'سجلات الأخطاء - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-bug"></i> سجلات الأخطاء
                        </h5>
                        <div>
                            <a href="{{ route('admin.error-logs.clear') }}" class="btn btn-warning me-2"
                                onclick="return confirm('هل أنت متأكد من حذف السجلات القديمة؟')">
                                <i class="bi bi-trash"></i> حذف السجلات القديمة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.error-logs.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="بحث في الرسائل"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="level" class="form-select">
                                        <option value="">جميع المستويات</option>
                                        <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>حرج
                                        </option>
                                        <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>خطأ
                                        </option>
                                        <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>تحذير
                                        </option>
                                        <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>معلومات
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.error-logs.index') }}" class="btn btn-secondary">إعادة
                                        تعيين</a>
                                </div>
                            </div>
                        </form>

                        <!-- الإحصائيات -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-danger text-white text-center">
                                    <div class="card-body py-2">
                                        <h6>أخطاء حرجة</h6>
                                        <h4>{{ $stats['critical'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark text-center">
                                    <div class="card-body py-2">
                                        <h6>أخطاء عادية</h6>
                                        <h4>{{ $stats['errors'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white text-center">
                                    <div class="card-body py-2">
                                        <h6>تحذيرات</h6>
                                        <h4>{{ $stats['warnings'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white text-center">
                                    <div class="card-body py-2">
                                        <h6>المجموع (30 يوم)</h6>
                                        <h4>{{ $stats['total'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- جدول سجلات الأخطاء -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>المستوى</th>
                                        <th>الرسالة</th>
                                        <th>المستخدم</th>
                                        <th>التاريخ</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($errorLogs as $log)
                                        <tr>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $log->level == 'critical' ? 'danger' : ($log->level == 'error' ? 'warning' : ($log->level == 'warning' ? 'info' : 'secondary')) }}">
                                                    {{ $log->level }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($log->message, 70) }}</td>
                                            <td>
                                                @if ($log->user)
                                                    {{ $log->user->name }}
                                                @else
                                                    نظام
                                                @endif
                                            </td>
                                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.error-logs.show', $log) }}"
                                                    class="btn btn-sm btn-info">تفاصيل</a>
                                                <form action="{{ route('admin.error-logs.destroy', $log) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                        حذف
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- التصفح -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $errorLogs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
