@extends('layouts.admin')

@section('title', 'تفاصيل المريض - ' . $patient->full_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- رسائل التنبيه -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة المرضى
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.patient.edit', $patient) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                        <form action="{{ route('admin.patient.toggleStatus', $patient) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $patient->is_active ? 'warning' : 'success' }} me-2">
                                {{ $patient->is_active ? 'تعطيل' : 'تفعيل' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.patient.delete', $patient) }}" class="btn btn-danger">
                            <i class="bi bi-trash"></i> حذف
                        </a>
                    </div>
                </div>

                <!-- بطاقة تفاصيل المريض -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person"></i> تفاصيل المريض
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- الصورة والمعلومات الأساسية -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{ $patient->profile_image_url ?? 'https://via.placeholder.com/200' }}"
                                        class="rounded-circle mb-3" width="200" height="200" alt="صورة المريض">
                                    <h3>{{ $patient->full_name }}</h3>
                                    <p class="text-muted">{{ $patient->username }}</p>

                                    <div class="mb-3">
                                        <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }} fs-6">
                                            {{ $patient->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- معلومات الاتصال -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">معلومات الاتصال</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <i class="bi bi-envelope me-2 text-primary"></i>
                                            <a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a>
                                        </div>
                                        @if ($patient->phone)
                                            <div class="mb-2">
                                                <i class="bi bi-telephone me-2 text-primary"></i>
                                                <a href="tel:{{ $patient->phone }}">{{ $patient->phone }}</a>
                                            </div>
                                        @endif
                                        <div>
                                            <i class="bi bi-calendar me-2 text-primary"></i>
                                            انضم في: {{ $patient->created_at->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- التفاصيل الإضافية -->
                            <div class="col-md-8">
                                <!-- المعلومات الشخصية -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">المعلومات الشخصية</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <strong>تاريخ الميلاد:</strong><br>
                                                    @if ($patient->date_of_birth)
                                                        {{ $patient->date_of_birth->format('Y-m-d') }}
                                                        ({{ $patient->date_of_birth->age }} سنة)
                                                    @else
                                                        غير محدد
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <strong>الجنس:</strong><br>
                                                    @if ($patient->gender == 'male')
                                                        <span class="badge bg-info">ذكر</span>
                                                    @elseif($patient->gender == 'female')
                                                        <span class="badge bg-pink">أنثى</span>
                                                    @else
                                                        <span class="badge bg-secondary">غير محدد</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- الإحصائيات -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">الإحصائيات</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-primary">{{ $patient->appointments_count }}</h4>
                                                    <p class="mb-0">إجمالي المواعيد</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-success">{{ $patient->completed_appointments_count }}
                                                    </h4>
                                                    <p class="mb-0">مواعيد منتهية</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-info">{{ $patient->medical_records_count }}</h4>
                                                    <p class="mb-0">السجلات الطبية</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- الإجراءات السريعة -->
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">الإجراءات السريعة</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <a href="{{ route('admin.patient.medicalHistory', $patient) }}"
                                                    class="btn btn-outline-primary btn-block">
                                                    <i class="bi bi-file-medical fa-2x mb-2"></i>
                                                    <br>السجل الطبي
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="#" class="btn btn-outline-info btn-block">
                                                    <i class="bi bi-calendar-plus fa-2x mb-2"></i>
                                                    <br>حجز موعد
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="#" class="btn btn-outline-secondary btn-block">
                                                    <i class="bi bi-chat-dots fa-2x mb-2"></i>
                                                    <br>مراسلة
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- آخر المواعيد -->
                <div class="card mt-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">آخر المواعيد</h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">
                        @if ($patient->appointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>الطبيب</th>
                                            <th>المنشأة</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($patient->appointments->take(5) as $appointment)
                                            <tr>
                                                <td>{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                                <td>{{ $appointment->doctor->full_name }}</td>
                                                <td>{{ $appointment->facility->business_name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-info">عرض</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x display-4"></i>
                                <p class="mt-3">لا توجد مواعيد سابقة</p>
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
        .bg-pink {
            background-color: #e83e8c !important;
        }

        .border.rounded.p-3 {
            transition: all 0.3s ease;
        }

        .border.rounded.p-3:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .btn-block {
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // إضافة تأثيرات عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.card, .alert');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endpush
