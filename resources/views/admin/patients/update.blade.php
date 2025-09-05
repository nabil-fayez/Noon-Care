@extends('layouts.admin')

@section('title', 'تعديل بيانات المريض - ' . $patient->full_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            @include('admin.partials.sidebar')

            <div class="col-md-10">


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
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> تعديل بيانات المريض: {{ $patient->full_name }}
                    </h4>
                </div>

                <!-- نموذج التعديل -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات المريض</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.patient.update', $patient) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- الصورة الحالية -->
                                <div class="col-md-12 text-center mb-4">
                                    @if ($patient->profile_image_url)
                                        <img src="{{ $patient->profile_image_url }}" class="rounded-circle mb-3"
                                            width="150" height="150" alt="صورة المريض الحالية">
                                        <br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_image"
                                                id="remove_image" value="1">
                                            <label class="form-check-label text-danger" for="remove_image">
                                                حذف الصورة الحالية
                                            </label>
                                        </div>
                                    @else
                                        <img src="" class="rounded-circle mb-3" width="150" height="150"
                                            alt="لا توجد صورة">
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">اسم المستخدم <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username', $patient->username) }}"
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
                                            id="email" name="email" value="{{ old('email', $patient->email) }}"
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
                                            value="{{ old('first_name', $patient->first_name) }}" required>
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
                                            value="{{ old('last_name', $patient->last_name) }}" required>
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
                                            id="phone" name="phone" value="{{ old('phone', $patient->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                        <input type="date"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth" name="date_of_birth"
                                            value="{{ old('date_of_birth', $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">الجنس</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
                                            <option value="">اختر الجنس</option>
                                            <option value="male"
                                                {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>ذكر
                                            </option>
                                            <option value="female"
                                                {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>أنثى
                                            </option>
                                        </select>
                                        @error('gender')
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

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $patient->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    الحساب نشط
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
                                <a href="{{ route('admin.patient.show', $patient) }}" class="btn btn-secondary">
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
    </script>
@endpush
