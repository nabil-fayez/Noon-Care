@extends('layouts.admin')

@section('title', 'تأكيد حذف التخصص - ' . $specialty->name . ' - Noon Care')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- رسائل التنبيه -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- أزرار التنقل -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('admin.specialty.show', $specialty) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> العودة إلى تفاصيل التخصص
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
                        <i class="bi bi-trash"></i> تأكيد حذف التخصص
                    </h5>
                </div>
                <div class="card-body">
                    <!-- تحذير -->
                    <div class="alert alert-warning" role="alert">
                        <h5 class="alert-heading">
                            <i class="bi bi-exclamation-octagon"></i> تحذير مهم!
                        </h5>
                        <p class="mb-0">
                            أنت على وشك حذف تخصص من النظام. هذه العملية لا يمكن التراجع عنها.
                        </p>
                    </div>

                    <!-- معلومات التخصص -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            @if($specialty->icon_url)
                                <img src="{{ $specialty->icon_url }}" class="rounded mb-3" width="120" height="120" alt="أيقونة التخصص">
                            @else
                                <div class="bg-{{ $specialty->color }} rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 120px; height: 120px;">
                                    <i class="bi bi-heart-pulse text-white fs-1"></i>
                                </div>
                            @endif
                            <h4>{{ $specialty->name }}</h4>
                            <p class="text-muted">{{ $specialty->description ? Str::limit($specialty->description, 50) : 'لا يوجد وصف' }}</p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>الحالة:</strong><br>
                                        <span class="badge bg-{{ $specialty->is_active ? 'success' : 'secondary' }}">
                                            {{ $specialty->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>عدد الأطباء:</strong><br>
                                        <span class="badge bg-info">{{ $specialty->doctors_count }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>تاريخ الإنشاء:</strong><br>
                                {{ $specialty->created_at->format('Y-m-d') }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>آخر تحديث:</strong><br>
                                {{ $specialty->updated_at->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>

                    <!-- التحذيرات -->
                    @if($specialty->doctors_count > 0)
                        <div class="alert alert-danger">
                            <h6 class="mb-3">
                                <i class="bi bi-exclamation-triangle"></i> تحذير هام:
                            </h6>
                            <p class="mb-0">
                                هذا التخصص لديه {{ $specialty->doctors_count }} طبيب مرتبط به. عند الحذف، سيتم فصل جميع الأطباء عن هذا التخصص.
                            </p>
                        </div>
                    @endif

                    <!-- نموذج الحذف -->
                    <form method="POST" action="{{ route('admin.specialty.destroy', $specialty) }}" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">سبب الحذف (اختياري)</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" 
                                      placeholder="أدخل سبب الحذف للمراجعة المستقبلية...">{{ old('reason') }}</textarea>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                            <label class="form-check-label text-danger" for="confirmDelete">
                                أنا أدرك عواقب هذا الإجراء وأريد المتابعة في حذف هذا التخصص
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.specialty.show', $specialty) }}" class="btn btn-secondary">
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
                        <li>سيتم <strong>حذف التخصص بشكل نهائي</strong> من قاعدة البيانات</li>
                        <li>سيتم <strong>فصل جميع الأطباء</strong> عن هذا التخصص</li>
                        <li>لن يتمكن الأطباء من استخدام هذا التخصص في ملفاتهم الشخصية</li>
                        <li>لن يظهر هذا التخصص في خيارات البحث أو الفلاتر</li>
                        <li>هذا الإجراء <strong class="text-danger">لا يمكن التراجع عنه</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تفعيل/تعطيل زر الحذف بناءً على تأكيد المستخدم
    document.addEventListener('DOMContentLoaded', function() {
        const confirmCheckbox = document.getElementById('confirmDelete');
        const submitButton = document.getElementById('submitButton');
        
        if (confirmCheckbox && submitButton) {
            confirmCheckbox.addEventListener('change', function() {
                submitButton.disabled = !this.checked;
                
                // تغيير المظهر المرئي
                if (this.checked) {
                    submitButton.classList.remove('btn-secondary');
                    submitButton.classList.add('btn-danger');
                } else {
                    submitButton.classList.remove('btn-danger');
                    submitButton.classList.add('btn-secondary');
                }
            });
            
            // تهيئة الحالة الأولى للزر
            submitButton.disabled = !confirmCheckbox.checked;
        }
    });

    // تأكيد إضافي قبل الإرسال
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        if (!confirm('هل أنت متأكد تماماً من أنك تريد حذف هذا التخصص؟ لا يمكن التراجع عن هذا الإجراء.')) {
            e.preventDefault();
        }
    });
</script>
@endpush

@push('styles')
<style>
    .card-border-danger {
        border: 2px solid #dc3545;
    }
    
    #submitButton:disabled {
        cursor: not-allowed;
        opacity: 0.6;
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endpush