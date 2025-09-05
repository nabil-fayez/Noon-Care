@extends('layouts.app')

@section('title', 'تأكيد حذف المريض - ' . $patient->full_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">


                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة المرضى
                        </a>
                        <a href="{{ route('admin.patient.show', $patient) }}" class="btn btn-info ms-2">
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
                            <i class="bi bi-trash"></i> تأكيد حذف المريض
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- تحذير -->
                        <div class="alert alert-warning" role="alert">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-octagon"></i> تحذير مهم!
                            </h5>
                            <p class="mb-0">
                                أنت على وشك حذف مريض من النظام. هذه العملية ستحذف المريض ولكن يمكن استعادته لاحقاً من سلة
                                المحذوفات.
                            </p>
                        </div>

                        <!-- معلومات المريض -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ $patient->profile_image_url  }}"
                                    class="rounded-circle mb-3" width="150" height="150" alt="صورة المريض">
                                <h4>{{ $patient->full_name }}</h4>
                                <p class="text-muted">{{ $patient->username }}</p>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>البريد الإلكتروني:</strong><br>
                                            {{ $patient->email }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>رقم الهاتف:</strong><br>
                                            {{ $patient->phone ?? 'غير متوفر' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>العمر:</strong><br>
                                            @if ($patient->date_of_birth)
                                                {{ $patient->date_of_birth->age }} سنة
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

                                <div class="mb-3">
                                    <strong>حالة الحساب:</strong><br>
                                    <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }}">
                                        {{ $patient->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <strong>تاريخ التسجيل:</strong><br>
                                    {{ $patient->created_at->format('Y-m-d') }}
                                </div>
                            </div>
                        </div>

                        <!-- الإحصائيات والتحذيرات -->
                        <div class="alert alert-info">
                            <h6 class="mb-3">
                                <i class="bi bi-info-circle"></i> معلومات مهمة قبل الحذف:
                            </h6>
                            <ul class="mb-0">
                                <li>عدد المواعيد المرتبطة: {{ $patient->appointments_count }} موعد</li>
                                <li>عدد السجلات الطبية: {{ $patient->medical_records_count }} سجل</li>
                                <li>عدد المراجعات: {{ $patient->reviews_count }} مراجعة</li>
                            </ul>
                        </div>

                        @if ($patient->appointments_count > 0)
                            <div class="alert alert-danger">
                                <h6 class="mb-3">
                                    <i class="bi bi-exclamation-triangle"></i> تحذير هام:
                                </h6>
                                <p class="mb-0">
                                    هذا المريض لديه {{ $patient->appointments_count }} موعد مرتبط به. عند الحذف، سيتم إلغاء
                                    جميع المواعيد المستقبلية المرتبطة بهذا المريض.
                                </p>
                            </div>
                        @endif

                        <!-- نموذج الحذف -->
                        <form method="POST" action="{{ route('admin.patient.destroy', $patient) }}" id="deleteForm">
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
                                    أنا أدرك عواقب هذا الإجراء وأريد المتابعة في حذف هذا المريض
                                </label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.patient.show', $patient) }}" class="btn btn-secondary">
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
                            <li>سيتم نقل المريض إلى <strong>سلة المحذوفات</strong> وليس الحذف النهائي</li>
                            <li>يمكن استعادة المريض في أي وقت من خلال سلة المحذوفات</li>
                            <li>سيتم إلغاء جميع المواعيد المستقبلية للمريض</li>
                            <li>ستبقى سجلات المواعيد السابقة محفوظة في النظام</li>
                            <li>لن يتمكن المريض من تسجيل الدخول حتى يتم استعادته</li>
                        </ul>
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
        // تأكيد إضافي قبل الإرسال
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            if (!confirm(
                    'هل أنت متأكد تماماً من أنك تريد حذف هذا المريض؟ لا يمكن التراجع عن هذا الإجراء إلا عن طريق الاستعادة من سلة المحذوفات.'
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
