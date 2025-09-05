@extends('layouts.patient')

@section('title', 'تعديل الملف الشخصي - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('patient.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>تعديل الملف الشخصي</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('patient.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img id="profileImagePreview"
                                        src="{{ auth()->guard('patient')->user()->profile_image_url }}"
                                        class="rounded-circle" width="150" height="150">
                                    <div class="mt-3">
                                        <label for="profile_image" class="btn btn-outline-primary btn-sm">
                                            تغيير الصورة
                                            <input type="file" id="profile_image" name="profile_image" class="d-none"
                                                accept="image/*" onchange="previewImage(this)">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label">الاسم الأول</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name"
                                                    value="{{ old('first_name', auth()->guard('patient')->user()->first_name) }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label">الاسم الأخير</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name"
                                                    value="{{ old('last_name', auth()->guard('patient')->user()->last_name) }}"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', auth()->guard('patient')->user()->email) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone', auth()->guard('patient')->user()->phone) }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                                <input type="date" class="form-control" id="date_of_birth"
                                                    name="date_of_birth"
                                                    value="{{ old('date_of_birth', auth()->guard('patient')->user()->date_of_birth ? auth()->guard('patient')->user()->date_of_birth->format('Y-m-d') : '') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">الجنس</label>
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="male"
                                                        {{ old('gender', auth()->guard('patient')->user()->gender) == 'male' ? 'selected' : '' }}>
                                                        ذكر</option>
                                                    <option value="female"
                                                        {{ old('gender', auth()->guard('patient')->user()->gender) == 'female' ? 'selected' : '' }}>
                                                        أنثى</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="blood_type" class="form-label">فصيلة الدم</label>
                                        <select class="form-control" id="blood_type" name="blood_type">
                                            <option value="">اختر...</option>
                                            <option value="A+"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'A+' ? 'selected' : '' }}>
                                                A+</option>
                                            <option value="A-"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'A-' ? 'selected' : '' }}>
                                                A-</option>
                                            <option value="B+"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'B+' ? 'selected' : '' }}>
                                                B+</option>
                                            <option value="B-"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'B-' ? 'selected' : '' }}>
                                                B-</option>
                                            <option value="AB+"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'AB+' ? 'selected' : '' }}>
                                                AB+</option>
                                            <option value="AB-"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'AB-' ? 'selected' : '' }}>
                                                AB-</option>
                                            <option value="O+"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'O+' ? 'selected' : '' }}>
                                                O+</option>
                                            <option value="O-"
                                                {{ old('blood_type', auth()->guard('patient')->user()->blood_type) == 'O-' ? 'selected' : '' }}>
                                                O-</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">جهة الاتصال في حالات
                                            الطوارئ</label>
                                        <input type="text" class="form-control" id="emergency_contact"
                                            name="emergency_contact"
                                            value="{{ old('emergency_contact', auth()->guard('patient')->user()->emergency_contact) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">العنوان</label>
                                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', auth()->guard('patient')->user()->address) }}</textarea>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
