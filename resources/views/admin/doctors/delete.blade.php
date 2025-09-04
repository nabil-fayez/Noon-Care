@extends('layouts.admin')

@section('title', 'تأكيد حذف الطبيب - ' . $doctor->full_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
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
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة الأطباء
                        </a>
                        <a href="{{ route('admin.doctor.show', $doctor) }}" class="btn btn-info ms-2">
                            <i class="bi bi-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                    <h4 class="mb-0 text-danger">
                        <i class="bi bi-exclamation-triangle"></i> تأكيد الحذف
                    </h4>
                </div>

                <!-- بطاقة تأكيد الحذف -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-trash"></i> تأكيد حذف الطبيب
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- تحذير -->
                        <div class="alert alert-warning" role="alert">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-octagon"></i> تحذير مهم!
                            </h5>
                            <p class="mb-0">
                                أنت على وشك حذف طبيب من النظام. هذه العملية ستحذف الطبيب ولكن يمكن استعادته لاحقاً من سلة
                                المحذوفات.
                            </p>
                        </div>

                        <!-- معلومات الطبيب -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ $doctor->profile_image ?? 'https://via.placeholder.com/150' }}"
                                    class="rounded-circle mb-3" width="150" height="150" alt="صورة الطبيب">
                                <h4>{{ $doctor->full_name }}</h4>
                                <p class="text-muted">{{ $doctor->username }}</p>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>البريد الإلكتروني:</strong><br>
                                            {{ $doctor->email }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>رقم الهاتف:</strong><br>
                                            {{ $doctor->phone ?? 'غير متوفر' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>التخصصات:</strong><br>
                                    @if ($doctor->specialties->count() > 0)
                                        @foreach ($doctor->specialties as $specialty)
                                            <span class="badge bg-primary mb-1">{{ $specialty->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">لا توجد تخصصات</span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <strong>حالة التوثيق:</strong><br>
                                    <span class="badge bg-{{ $doctor->is_verified ? 'success' : 'warning' }}">
                                        {{ $doctor->is_verified ? 'موثق' : 'غير موثق' }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <strong>تاريخ الإنشاء:</strong><br>
                                    {{ $doctor->created_at->format('Y-m-d') }}
                                </div>
                            </div>
                        </div>

                        <!-- الإحصائيات والتحذيرات -->
                        <div class="alert alert-info">
                            <h6 class="mb-3">
                                <i class="bi bi-info-circle"></i> معلومات مهمة قبل الحذف:
                            </h6>
                            <ul class="mb-0">
                                <li>عدد المواعيد المرتبطة: {{ $doctor->appointments_count }} موعد</li>
                                <li>عدد التقييمات: {{ $doctor->reviews_count }} تقييم</li>
                                <li>عدد المنشآت المرتبطة: {{ $doctor->facilities_count }} منشأة</li>
                            </ul>
                        </div>

                        @if ($doctor->appointments_count > 0)
                            <div class="alert alert-danger">
                                <h6 class="mb-3">
                                    <i class="bi bi-exclamation-triangle"></i> تحذير هام:
                                </h6>
                                <p class="mb-0">
                                    هذا الطبيب لديه {{ $doctor->appointments_count }} موعد مرتبط به. عند الحذف، سيتم إلغاء
                                    جميع المواعيد المستقبلية المرتبطة بهذا الطبيب.
                                </p>
                            </div>
                        @endif

                        <!-- نموذج الحذف -->
                        <form method="POST" action="{{ route('admin.doctor.destroy', $doctor) }}" id="deleteForm">
                            @csrf
                            @method('DELETE')

                            <div class="mb-3">
                                <label for="reason" class="form-label">سبب الحذف (اختياري)</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3"
                                    placeholder="أدخل سبب الحذف للمراجعة المستقبلية...">{{ old('reason') }}</textarea>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="confirmDelete" required
                                    onchange="document.getElementById('submitButton').disabled = !this.checked;">
                                <label class="form-check-label text-danger" for="confirmDelete">
                                    أنا أدرك عواقب هذا الإجراء وأريد المتابعة في حذف هذا الطبيب
                                </label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.doctor.show', $doctor) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> إلغاء والعودة
                                    </a>
                                </div>
                                <button type="submit" class="btn btn-danger" id="submitButton" disabled>
                                    <i class="bi bi-trash"></i> تأكيد الحذف
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">ماذا يحدث بعد الحذف؟</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>سيتم نقل الطبيب إلى <strong>سلة المحذوفات</strong> وليس الحذف النهائي</li>
                            <li>يمكن استعادة الطبيب في أي وقت من خلال سلة المحذوفات</li>
                            <li>سيتم إلغاء جميع المواعيد المستقبلية للطبيب</li>
                            <li>ستبقى سجلات المواعيد السابقة محفوظة في النظام</li>
                            <li>لن يتمكن الطبيب من تسجيل الدخول حتى يتم استعادته</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-border-danger {
            border: 2px solid #dc3545;
        }

        .alert-warning {
            border-left: 4px solid #ffc107;
        }

        .alert-danger {
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            border-left: 4px solid #0dcaf0;
        }

        #submitButton:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const checkbox = document.getElementById('confirmDelete');
        const button = document.getElementById('submitButton');

        checkbox.addEventListener('change', () => {
            console.log('1');

            button.disabled = !checkbox.checked;
        });

        // // تفعيل/تعطيل زر الحذف بناءً على تأكيد المستخدم
        // const checkbox = document.getElementById('confirmDelete');
        // const button = document.getElementById('submitButton');

        // checkbox.addEventListener('click', () => {
        //     button.style.display == 'none' ? 'block' : 'none';
        // });

        // تأكيد إضافي قبل الإرسال
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            if (!confirm(
                    'هل أنت متأكد تماماً من أنك تريد حذف هذا الطبيب؟ لا يمكن التراجع عن هذا الإجراء إلا عن طريق الاستعادة من سلة المحذوفات.'
                )) {
                e.preventDefault();
            }
        });

        // إضافة تأثيرات عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';

            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, 100);
        });
    </script>
@endpush
