@extends('layouts.admin')

@section('title', 'تفاصيل المنشأة - ' . $facility->business_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">


                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة المنشآت
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.facility.edit', $facility) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                        <form action="{{ route('admin.facility.toggleStatus', $facility) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $facility->is_active ? 'warning' : 'success' }} me-2">
                                {{ $facility->is_active ? 'تعطيل' : 'تفعيل' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.facility.delete', $facility) }}" class="btn btn-danger">
                            <i class="bi bi-trash"></i> حذف
                        </a>
                    </div>
                </div>

                <!-- بطاقة تفاصيل المنشأة -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-building"></i> تفاصيل المنشأة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- الشعار والمعلومات الأساسية -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{ $facility->logo_url }}"
                                        class="rounded mb-3" width="200" height="200" alt="شعار المنشأة">
                                    <h3>{{ $facility->business_name }}</h3>
                                    <p class="text-muted">{{ $facility->username }}</p>

                                    <div class="mb-3">
                                        <span class="badge bg-{{ $facility->is_active ? 'success' : 'secondary' }} fs-6">
                                            {{ $facility->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- معلومات الاتصال -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">معلومات الاتصال</h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($facility->email)
                                            <div class="mb-2">
                                                <i class="bi bi-envelope me-2 text-primary"></i>
                                                <a href="mailto:{{ $facility->email }}">{{ $facility->email }}</a>
                                            </div>
                                        @endif
                                        @if ($facility->phone)
                                            <div class="mb-2">
                                                <i class="bi bi-telephone me-2 text-primary"></i>
                                                <a href="tel:{{ $facility->phone }}">{{ $facility->phone }}</a>
                                            </div>
                                        @endif
                                        @if ($facility->website)
                                            <div class="mb-2">
                                                <i class="bi bi-globe me-2 text-primary"></i>
                                                <a href="{{ $facility->website }}"
                                                    target="_blank">{{ $facility->website }}</a>
                                            </div>
                                        @endif
                                        <div>
                                            <i class="bi bi-calendar me-2 text-primary"></i>
                                            انضم في: {{ $facility->created_at->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- الموقع -->
                                @if ($facility->latitude && $facility->longitude)
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">الموقع</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>خط العرض:</strong> {{ $facility->latitude }}
                                            </div>
                                            <div class="mb-2">
                                                <strong>خط الطول:</strong> {{ $facility->longitude }}
                                            </div>
                                            <a href="https://maps.google.com/?q={{ $facility->latitude }},{{ $facility->longitude }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-map"></i> عرض على الخريطة
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- التفاصيل الإضافية -->
                            <div class="col-md-8">
                                <!-- المعلومات العامة -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">المعلومات العامة</h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($facility->address)
                                            <div class="mb-3">
                                                <strong>العنوان:</strong><br>
                                                {{ $facility->address }}
                                            </div>
                                        @endif
                                        @if ($facility->description)
                                            <div class="mb-3">
                                                <strong>الوصف:</strong><br>
                                                {{ $facility->description }}
                                            </div>
                                        @endif
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
                                                    <h4 class="text-primary">{{ $facility->doctors_count }}</h4>
                                                    <p class="mb-0">عدد الأطباء</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-success">{{ $facility->services_count }}</h4>
                                                    <p class="mb-0">عدد الخدمات</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-info">{{ $facility->appointments_count }}</h4>
                                                    <p class="mb-0">عدد المواعيد</p>
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
                                                <a href="{{ route('admin.facility.doctors', $facility) }}"
                                                    class="btn btn-outline-primary btn-block">
                                                    <i class="bi bi-people fa-2x mb-2"></i>
                                                    <br>الأطباء
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{ route('admin.facility.services', $facility) }}"
                                                    class="btn btn-outline-info btn-block">
                                                    <i class="bi bi-list-check fa-2x mb-2"></i>
                                                    <br>الخدمات
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{ route('admin.facility.appointments', $facility) }}"
                                                    class="btn btn-outline-success btn-block">
                                                    <i class="bi bi-calendar-check fa-2x mb-2"></i>
                                                    <br>المواعيد
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- قائمة الأطباء -->
                <div class="card mt-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">أطباء المنشأة</h6>
                        <a href="{{ route('admin.facility.doctors', $facility) }}"
                            class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">
                        @if ($facility->doctors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>الصورة</th>
                                            <th>اسم الطبيب</th>
                                            <th>التخصصات</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($facility->doctors->take(5) as $doctor)
                                            <tr>
                                                <td>
                                                    <img src="{{ $doctor->profile_image_url }}" class="rounded-circle"
                                                        width="40" height="40" alt="صورة الطبيب">
                                                </td>
                                                <td>{{ $doctor->full_name }}</td>
                                                <td>
                                                    @foreach ($doctor->specialties->take(2) as $specialty)
                                                        <span class="badge bg-info mb-1">{{ $specialty->name }}</span>
                                                    @endforeach
                                                    @if ($doctor->specialties->count() > 2)
                                                        <span
                                                            class="badge bg-secondary">+{{ $doctor->specialties->count() - 2 }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $doctor->pivot->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ $doctor->pivot->status === 'active' ? 'نشط' : 'غير نشط' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.doctor.show', $doctor) }}"
                                                        class="btn btn-sm btn-info">عرض</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-person-x display-4"></i>
                                <p class="mt-3">لا يوجد أطباء في هذه المنشأة</p>
                                <a href="{{ route('admin.facility.doctors', $facility) }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> إضافة أطباء
                                </a>
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
