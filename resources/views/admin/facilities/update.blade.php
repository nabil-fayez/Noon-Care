@extends('layouts.admin')

@section('title', 'تعديل بيانات المنشأة - ' . $facility->business_name . ' - Noon Care')

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
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة المنشآت
                        </a>
                        <a href="{{ route('admin.facility.show', $facility) }}" class="btn btn-info ms-2">
                            <i class="bi bi-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> تعديل بيانات المنشأة: {{ $facility->business_name }}
                    </h4>
                </div>

                <!-- نموذج التعديل -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات المنشأة</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.facility.update', $facility) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- الشعار الحالي -->
                                <div class="col-md-12 text-center mb-4">
                                    @if ($facility->logo)
                                        <img src="{{ $facility->logo_url }}" class="rounded mb-3" width="150"
                                            height="150" alt="شعار المنشأة الحالي">
                                        <br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_logo"
                                                id="remove_logo" value="1">
                                            <label class="form-check-label text-danger" for="remove_logo">
                                                حذف الشعار الحالي
                                            </label>
                                        </div>
                                    @else
                                        <img src="https://via.placeholder.com/150" class="rounded mb-3" width="150"
                                            height="150" alt="لا يوجد شعار">
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">اسم المستخدم <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username"
                                            value="{{ old('username', $facility->username) }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $facility->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="business_name" class="form-label">اسم المنشأة <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                    id="business_name" name="business_name"
                                    value="{{ old('business_name', $facility->business_name) }}" required>
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $facility->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">الموقع الإلكتروني</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website"
                                            value="{{ old('website', $facility->website) }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $facility->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $facility->description) }}</textarea>
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
                                            name="latitude" value="{{ old('latitude', $facility->latitude) }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">خط الطول</label>
                                        <input type="number" step="any"
                                            class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                            name="longitude" value="{{ old('longitude', $facility->longitude) }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $facility->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    المنشأة نشطة
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
                                <a href="{{ route('admin.facility.show', $facility) }}" class="btn btn-secondary">
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
                        <p class="mb-1">معاينة الشعار الجديد:</p>
                        <img src="${e.target.result}" class="rounded" width="100" height="100" alt="معاينة الشعار">
                    `;

                    const existingPreview = document.getElementById('logo-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    preview.id = 'logo-preview';
                    document.querySelector('.col-md-12.text-center').appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
