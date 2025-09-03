@extends('layouts.admin')

@section('title', 'إضافة منشأة طبية جديدة - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-building-add"></i> إضافة منشأة طبية جديدة
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.facility.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">اسم المستخدم <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username') }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">كلمة المرور <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="business_name" class="form-label">اسم المنشأة <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                    id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">الموقع الإلكتروني</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website" value="{{ old('website') }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">الشعار</label>
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                            id="logo" name="logo" accept="image/*">
                                        <div class="form-text">يسمح بملفات الصور فقط (JPG, PNG, GIF) - الحجم الأقصى: 2MB
                                        </div>
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">خط العرض</label>
                                        <input type="number" step="any"
                                            class="form-control @error('latitude') is-invalid @enderror" id="latitude"
                                            name="latitude" value="{{ old('latitude') }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">خط الطول</label>
                                        <input type="number" step="any"
                                            class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                            name="longitude" value="{{ old('longitude') }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    المنشأة نشطة
                                </label>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> حفظ
                                </button>
                                <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
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
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'mt-3 text-center';
                    preview.innerHTML = `
                        <p class="mb-1">معاينة الشعار:</p>
                        <img src="${e.target.result}" class="rounded" width="100" height="100" alt="معاينة الشعار">
                    `;

                    const existingPreview = document.getElementById('logo-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    preview.id = 'logo-preview';
                    document.querySelector('.card-body').insertBefore(preview, document.querySelector(
                        '.form-check'));
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
