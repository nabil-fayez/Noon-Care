@extends('layouts.admin')

@section('title', 'تعديل صلاحية - ' . $permission->permission_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">


                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة الصلاحيات
                        </a>
                        <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-info ms-2">
                            <i class="bi bi-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> تعديل الصلاحية: {{ $permission->permission_name }}
                    </h4>
                </div>

                <!-- نموذج التعديل -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات الصلاحية</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="permission_name" class="form-label">اسم الصلاحية <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('permission_name') is-invalid @enderror"
                                            id="permission_name" name="permission_name"
                                            value="{{ old('permission_name', $permission->permission_name) }}" required>
                                        @error('permission_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="module" class="form-label">الوحدة النمطية <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('module') is-invalid @enderror" id="module"
                                            name="module" required>
                                            <option value="">اختر الوحدة النمطية</option>
                                            @foreach ($modules as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ old('module', $permission->module) == $key ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('module')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $permission->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-secondary">
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
