@extends('layouts.admin')

@section('title', 'تفاصيل سجل الخطأ - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.error-logs.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى سجلات الأخطاء
                        </a>
                    </div>
                    <h4 class="mb-0 text-{{ $errorLog->level == 'critical' ? 'danger' : ($errorLog->level == 'error' ? 'warning' : 'info') }}">
                        <i class="bi bi-bug"></i> تفاصيل سجل الخطأ
                    </h4>
                </div>

                <!-- بطاقة تفاصيل الخطأ -->
                <div class="card">
                    <div class="card-header bg-{{ $errorLog->level == 'critical' ? 'danger' : ($errorLog->level == 'error' ? 'warning' : 'info') }} text-white">
                        <h5 class="mb-0">معلومات الخطأ</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>مستوى الخطأ:</strong><br>
                                    <span class="badge bg-{{ $errorLog->level == 'critical' ? 'danger' : ($errorLog->level == 'error' ? 'warning' : ($errorLog->level == 'warning' ? 'info' : 'secondary') }}">
                                        {{ $errorLog->level }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>تاريخ الحدوث:</strong><br>
                                    {{ $errorLog->created_at->format('Y-m-d H:i:s') }}
                                    <br>
                                    <small class="text-muted">{{ $errorLog->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>الرسالة:</strong><br>
                            <div class="alert alert-{{ $errorLog->level == 'critical' ? 'danger' : ($errorLog->level == 'error' ? 'warning' : 'info') }}">
                                {{ $errorLog->message }}
                            </div>
                        </div>

                        @if($errorLog->file)
                        <div class="mb-3">
                            <strong>الملف:</strong><br>
                            <code>{{ $errorLog->file }}{{ $errorLog->line ? ':' . $errorLog->line : '' }}</code>
                        </div>
                        @endif

                        @if($errorLog->url)
                        <div class="mb-3">
                            <strong>الرابط:</strong><br>
                            <a href="{{ $errorLog->url }}" target="_blank">{{ $errorLog->url }}</a>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>عنوان IP:</strong><br>
                                    {{ $errorLog->ip ?? 'غير متوفر' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>معلومات المتصفح:</strong><br>
                                    {{ $errorLog->user_agent ?? 'غير متوفر' }}
                                </div>
                            </div>
                        </div>

                        @if($errorLog->user)
                        <div class="mb-3">
                            <strong>المستخدم:</strong><br>
                            <div class="d-flex align-items-center">
                                <img src="{{ $errorLog->user->profile_image_url ?? 'https://via.placeholder.com/40' }}"
                                     class="rounded-circle me-2" width="40" height="40" alt="صورة المستخدم">
                                <div>
                                    <strong>{{ $errorLog->user->name }}</strong><br>
                                    <small class="text-muted">{{ $errorLog->user->email }}</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($errorLog->details && is_array($errorLog->details))
                        <div class="mb-3">
                            <strong>التفاصيل الإضافية:</strong><br>
                            <div class="card">
                                <div class="card-body">
                                    <pre class="mb-0">{{ json_encode($errorLog->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <form action="{{ route('admin.error-logs.destroy', $errorLog) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('هل أنت متأكد من حذف سجل الخطأ هذا؟')">
                                <i class="bi bi-trash"></i> حذف سجل الخطأ
                            </button>
                        </form>
                        <a href="{{ route('admin.error-logs.index') }}" class="btn btn-secondary">
                            <i class="bi bi-list"></i> العودة إلى القائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            border: 1px solid #e9ecef;
        }
    </style>
@endpush
