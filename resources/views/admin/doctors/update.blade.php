@extends('layouts.admin')

@section('title', 'تعديل بيانات الطبيب - ' . $doctor->full_name . ' - Noon Care')

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
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة الأطباء
                        </a>
                        <a href="{{ route('admin.doctor.show', $doctor) }}" class="btn btn-info ms-2">
                            <i class="bi bi-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> تعديل بيانات الطبيب: {{ $doctor->full_name }}
                    </h4>
                </div>

                <!-- نموذج التعديل -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات الطبيب</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.doctor.update', $doctor) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- الصورة الحالية -->
                                <div class="col-md-12 text-center mb-4">
                                    @if ($doctor->profile_image)
                                        <img src="{{ $doctor->profile_image_url }}" class="rounded-circle mb-3"
                                            width="150" height="150" alt="صورة الطبيب الحالية">
                                        <br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_image"
                                                id="remove_image" value="1">
                                            <label class="form-check-label text-danger" for="remove_image">
                                                حذف الصورة الحالية
                                            </label>
                                        </div>
                                    @else
                                        <img src="https://via.placeholder.com/150" class="rounded-circle mb-3"
                                            width="150" height="150" alt="لا توجد صورة">
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">اسم المستخدم <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username', $doctor->username) }}"
                                            required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $doctor->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">الاسم الأول <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name"
                                            value="{{ old('first_name', $doctor->first_name) }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">الاسم الأخير <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name"
                                            value="{{ old('last_name', $doctor->last_name) }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $doctor->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">صورة الملف الشخصي</label>
                                        <input type="file"
                                            class="form-control @error('profile_image') is-invalid @enderror"
                                            id="profile_image" name="profile_image" accept="image/*">
                                        <div class="form-text">يسمح بملفات الصور فقط (JPG, PNG, GIF) - الحجم الأقصى: 2MB
                                        </div>
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">السيرة الذاتية</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4">{{ old('bio', $doctor->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

<div class="mb-3">
    <label class="form-label">التخصصات <span class="text-danger">*</span></label>
    <select class="form-select select2-multiple" name="specializations[]" multiple required>
        <option value="">اختر التخصصات</option>
        @foreach($specialties as $specialty)
            <option value="{{ $specialty->id }}" 
                    {{ in_array($specialty->id, old('specializations', $selectedSpecialties ?? [])) ? 'selected' : '' }}
                    data-color="{{ $specialty->color }}">
                {{ $specialty->name }}
            </option>
        @endforeach
    </select>
    @error('specializations')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified"
                                    value="1" {{ old('is_verified', $doctor->is_verified) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">
                                    الطبيب موثق
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password">
                                        <div class="form-text">اتركه فارغاً إذا لم ترد تغيير كلمة المرور</div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور
                                            الجديدة</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.doctor.show', $doctor) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }

        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // معاينة الصورة قبل الرفع
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // إنشاء عنصر للمعاينة
                    const preview = document.createElement('div');
                    preview.className = 'mt-3 text-center';
                    preview.innerHTML = `
                    <p class="mb-1">معاينة الصورة الجديدة:</p>
                    <img src="${e.target.result}" class="rounded-circle" width="100" height="100" alt="معاينة الصورة">
                `;

                    // إزالة أي معاينة سابقة
                    const existingPreview = document.getElementById('image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    preview.id = 'image-preview';
                    document.querySelector('.col-md-12.text-center').appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });

        // التأكد من اختيار تخصص واحد على الأقل
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkedSpecialties = document.querySelectorAll('input[name="specializations[]"]:checked');
            if (checkedSpecialties.length === 0) {
                e.preventDefault();
                alert('يجب اختيار تخصص واحد على الأقل');
            }
        });
    </script>
@endpush
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // تهيئة Select2 مع دعم الألوان
    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "اختر التخصصات",
            allowClear: true,
            templateResult: formatOption,
            templateSelection: formatOption
        });
        
        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            
            var color = $(option.element).data('color');
            var $option = $(
                '<span><span class="color-badge me-2" style="background-color: ' + color + '"></span>' + option.text + '</span>'
            );
            
            return $option;
        }
    });
</script>
@endpush

@push('styles')
<style>
    .color-badge {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        vertical-align: middle;
    }
    
    .select2-container--default .select2-selection--multiple {
        padding: 0.375rem 0.75rem;
        min-height: 38px;
    }
</style>
@endpush