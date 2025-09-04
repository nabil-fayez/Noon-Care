@extends('layouts.admin')

@section('title', 'إضافة طبيب جديد - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">إضافة طبيب جديد</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.doctor.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">اسم المستخدم</label>
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
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">الاسم الأول</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">الاسم الأخير</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">كلمة المرور</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>
                                </div>
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
                                        <label for="profile_image" class="form-label">صورة الملف الشخصي</label>
                                        <input type="file"
                                            class="form-control @error('profile_image') is-invalid @enderror"
                                            id="profile_image" name="profile_image" accept="image/*">
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">السيرة الذاتية</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">التخصصات <span class="text-danger">*</span></label>
                                <select class="form-select select2-multiple" name="specializations[]" multiple required>
                                    <option value="">اختر التخصصات</option>
                                    @foreach ($specialties as $specialty)
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

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified"
                                    value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">
                                    موثق
                                </label>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">حفظ</button>
                                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
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
                    '<span><span class="color-badge me-2" style="background-color: ' + color + '"></span>' +
                    option.text + '</span>'
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
