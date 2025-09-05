@extends('layouts.admin')

@section('title', 'تفاصيل الصلاحية - ' . $permission->permission_name . ' - Noon Care')

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
                        @if (request()->user()->hasPermission('permissions.update'))
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary ms-2">
                                <i class="bi bi-pencil"></i> تعديل
                            </a>
                        @endif
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock"></i> تفاصيل الصلاحية: {{ $permission->permission_name }}
                    </h4>
                </div>

                <!-- تفاصيل الصلاحية -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">معلومات أساسية</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>اسم الصلاحية:</strong>
                                    <p>{{ $permission->permission_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>الوحدة النمطية:</strong>
                                    <p>{{ $permission->module }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>الوصف:</strong>
                                    <p>{{ $permission->description ?? 'لا يوجد وصف' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>تاريخ الإنشاء:</strong>
                                    <p>{{ $permission->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>آخر تحديث:</strong>
                                    <p>{{ $permission->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">الأدوار المرتبطة</h5>
                            </div>
                            <div class="card-body">
                                @if ($permission->roles->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم الدور</th>
                                                    <th>عدد المسؤولين</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permission->roles as $role)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $role->role_name }}</td>
                                                        <td>{{ $role->admins_count }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center">لا توجد أدوار مرتبطة بهذه الصلاحية</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
