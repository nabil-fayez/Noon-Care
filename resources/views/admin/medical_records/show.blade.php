@extends('layouts.admin')

@section('title', $medicalRecord->title . ' - Noon Care')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ URL::previous() }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> رجوع
                        </a>
                    </div>
                    <div>
                        @if ($medicalRecord->has_attachment)
                            <a href="{{ $medicalRecord->attachment_url }}" class="btn btn-outline-primary me-2" download>
                                <i class="bi bi-download"></i> تحميل المرفق
                            </a>
                        @endif
                    </div>
                </div>

                <!-- بطاقة تفاصيل السجل الطبي -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-medical"></i> السجل الطبي
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h4 class="card-title">{{ $medicalRecord->title }}</h4>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge bg-{{ $medicalRecord->is_urgent ? 'danger' : 'secondary' }}">
                                        {{ $medicalRecord->record_type }}
                                    </span>
                                    <span
                                        class="badge bg-{{ $medicalRecord->status == 'active' ? 'success' : ($medicalRecord->status == 'completed' ? 'info' : 'warning') }}">
                                        {{ $medicalRecord->status == 'active' ? 'نشط' : ($medicalRecord->status == 'completed' ? 'مكتمل' : 'ملغي') }}
                                    </span>
                                    @if ($medicalRecord->is_urgent)
                                        <span class="badge bg-danger">عاجل</span>
                                    @endif
                                    @if ($medicalRecord->requires_follow_up)
                                        <span class="badge bg-warning">يتطلب متابعة</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <p class="text-muted mb-1">
                                    <i class="bi bi-calendar"></i>
                                    {{ $medicalRecord->record_date->format('Y-m-d') }}
                                </p>
                                @if ($medicalRecord->follow_up_date)
                                    <p class="text-muted">
                                        <i class="bi bi-arrow-repeat"></i>
                                        متابعة: {{ $medicalRecord->follow_up_date->format('Y-m-d') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- المعلومات الأساسية -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">معلومات الطبيب</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $medicalRecord->doctor->profile_image ?? 'https://via.placeholder.com/50' }}"
                                                class="rounded-circle me-3" width="50" height="50">
                                            <div>
                                                <h6 class="mb-0">{{ $medicalRecord->doctor->full_name }}</h6>
                                                <p class="text-muted mb-0">
                                                    @foreach ($medicalRecord->doctor->specialties as $specialty)
                                                        <span class="badge bg-info">{{ $specialty->name }}</span>
                                                    @endforeach
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">معلومات المنشأة</h6>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-0">{{ $medicalRecord->facility->business_name }}</h6>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ $medicalRecord->facility->address }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المحتوى الرئيسي -->
                        <div class="row">
                            @if ($medicalRecord->description)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">الوصف</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($medicalRecord->symptoms)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">الأعراض</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->symptoms }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($medicalRecord->diagnosis)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">التشخيص</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->diagnosis }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($medicalRecord->treatment_plan)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">خطة العلاج</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->treatment_plan }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($medicalRecord->prescription)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">الوصفة الطبية</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->prescription }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($medicalRecord->test_results)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">نتائج التحاليل</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->test_results }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($medicalRecord->notes)
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">ملاحظات إضافية</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $medicalRecord->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- المرفقات -->
                        @if ($medicalRecord->has_attachment)
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">المرفقات</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-paperclip fs-4 me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $medicalRecord->attachment_name }}</h6>
                                            <small class="text-muted">تم الرفع في
                                                {{ $medicalRecord->created_at->format('Y-m-d') }}</small>
                                        </div>
                                        <a href="{{ $medicalRecord->attachment_url }}" class="btn btn-outline-primary"
                                            download>
                                            <i class="bi bi-download"></i> تحميل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header.bg-light {
            background-color: #f8f9fa !important;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
@endpush
