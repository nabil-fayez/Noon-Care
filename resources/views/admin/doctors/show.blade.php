@extends('layouts.admin')

@section('title', 'تفاصيل الطبيب - ' . $doctor->full_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">

                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة الأطباء
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.doctor.edit', $doctor) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                        <a href="{{ route('admin.doctor.delete', $doctor) }}" class="btn btn-danger">
                            <i class="bi bi-trash"></i> حذف
                        </a>
                    </div>
                </div>

                <!-- بطاقة تفاصيل الطبيب -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge"></i> تفاصيل الطبيب
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- الصورة والمعلومات الأساسية -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{ $doctor->profile_image_url  }}"
                                        class="rounded-circle mb-3" width="200" height="200" alt="صورة الطبيب">
                                    <h3>{{ $doctor->full_name }}</h3>
                                    <p class="text-muted">{{ $doctor->username }}</p>

                                    <div class="mb-3">
                                        <span class="badge bg-{{ $doctor->is_verified ? 'success' : 'warning' }} fs-6">
                                            {{ $doctor->is_verified ? 'موثق' : 'غير موثق' }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-center gap-2 mb-3">
                                        <form action="{{ route('admin.doctor.toggleVerification', $doctor) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm btn-{{ $doctor->is_verified ? 'warning' : 'success' }}">
                                                {{ $doctor->is_verified ? 'إلغاء التوثيق' : 'توثيق' }}
                                            </button>
                                        </form>
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
                                            <a href="mailto:{{ $doctor->email }}">{{ $doctor->email }}</a>
                                        </div>
                                        @if ($doctor->phone)
                                            <div class="mb-2">
                                                <i class="bi bi-telephone me-2 text-primary"></i>
                                                <a href="tel:{{ $doctor->phone }}">{{ $doctor->phone }}</a>
                                            </div>
                                        @endif
                                        <div>
                                            <i class="bi bi-calendar me-2 text-primary"></i>
                                            انضم في: {{ $doctor->created_at->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- التفاصيل الإضافية -->
                            <div class="col-md-8">
                                <!-- السيرة الذاتية -->
                                @if ($doctor->bio)
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">السيرة الذاتية</h6>
                                        </div>
                                        <div class="card-body">
                                            <p>{{ $doctor->bio }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- التخصصات -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">التخصصات</h6>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#specialtiesModal">
                                            <i class="bi bi-pencil"></i> تعديل
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @if ($doctor->specialties->count() > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($doctor->specialties as $specialty)
                                                    <span class="badge bg-primary fs-6">{{ $specialty->name }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">لا توجد تخصصات مضافة</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- المنشآت الطبية -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">المنشآت الطبية</h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($doctor->facilities->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>اسم المنشأة</th>
                                                            <th>الحالة</th>
                                                            <th>متاح للمواعيد</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($doctor->facilities as $facility)
                                                            <tr>
                                                                <td>{{ $facility->business_name }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-{{ $facility->pivot->status === 'active' ? 'success' : ($facility->pivot->status === 'pending' ? 'warning' : 'secondary') }}">
                                                                        {{ $facility->pivot->status === 'active' ? 'نشط' : ($facility->pivot->status === 'pending' ? 'قيد الانتظار' : 'غير نشط') }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-{{ $facility->pivot->available_for_appointments ? 'success' : 'secondary' }}">
                                                                        {{ $facility->pivot->available_for_appointments ? 'نعم' : 'لا' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">لا توجد منشآت طبية مرتبطة</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- الإحصائيات -->
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">الإحصائيات</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-primary">{{ $doctor->appointments->count() }}</h4>
                                                    <p class="mb-0">إجمالي المواعيد</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-success">
                                                        {{ $doctor->appointments->where('status', 'done')->count() }}</h4>
                                                    <p class="mb-0">مواعيد منتهية</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-info">{{ $doctor->reviews->count() }}</h4>
                                                    <p class="mb-0">التقييمات</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لتعديل التخصصات -->
    <div class="modal fade" id="specialtiesModal" tabindex="-1" aria-labelledby="specialtiesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="specialtiesModalLabel">تعديل تخصصات الطبيب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.doctor.updateSpecialties', $doctor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اختر التخصصات</label>
                            <div class="row">
                                @foreach ($allSpecialties as $specialty)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="specialties[]"
                                                value="{{ $specialty->id }}" id="specialty{{ $specialty->id }}"
                                                {{ in_array($specialty->id, $doctor->specialties->pluck('id')->toArray()) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="specialty{{ $specialty->id }}">
                                                {{ $specialty->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .card-header.bg-light {
            background-color: #f8f9fa !important;
        }

        .badge.fs-6 {
            font-size: 0.9em !important;
            padding: 0.5em 0.75em;
        }

        .border.rounded.p-3 {
            transition: all 0.3s ease;
        }

        .border.rounded.p-3:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // إضافة تأثيرات عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // تأثيرات الظهور للعناصر
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
