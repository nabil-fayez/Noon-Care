@extends('layouts.admin')

@section('title', 'تعديل دور - ' . $role->role_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">


                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة الأدوار
                        </a>
                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info ms-2">
                            <i class="bi bi-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> تعديل الدور: {{ $role->role_name }}
                    </h4>
                </div>

                <!-- نموذج التعديل -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات الدور</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role_name" class="form-label">اسم الدور <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('role_name') is-invalid @enderror"
                                            id="role_name" name="role_name" value="{{ old('role_name', $role->role_name) }}"
                                            required>
                                        @error('role_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">الوصف</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="1">{{ old('description', $role->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default"
                                    value="1" {{ old('is_default', $role->is_default) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">
                                    تعيين كدور افتراضي
                                </label>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">الصلاحيات <span class="text-danger">*</span></label>

                                <!-- حقل البحث -->
                                <div class="mb-3">
                                    <input type="text" id="permission-search" class="form-control"
                                        placeholder="ابحث عن صلاحية...">
                                </div>

                                <!-- مربع الصلاحيات -->
                                <div class="permissions-container border rounded p-3"
                                    style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($groupedPermissions as $module => $permissions)
                                        <div class="module-group mb-4">
                                            <h6 class="mb-3 border-bottom pb-2">{{ $module }}</h6>
                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-4 mb-2 permission-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="permissions[]" value="{{ $permission->id }}"
                                                                id="permission-{{ $permission->id }}"
                                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="permission-{{ $permission->id }}">
                                                                {{ $permission->permission_name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('permissions')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-secondary">
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
        // وظيفة البحث في الصلاحيات
        document.getElementById('permission-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const permissionItems = document.querySelectorAll('.permission-item');

            permissionItems.forEach(item => {
                const label = item.querySelector('.form-check-label').textContent.toLowerCase();
                if (label.includes(searchTerm)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .permission-item {
            transition: all 0.3s ease;
        }

        .permission-item.hidden {
            display: none;
        }

        .module-group:last-child {
            margin-bottom: 0 !important;
        }
    </style>
@endpush
