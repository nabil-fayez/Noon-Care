@extends('layouts.admin')

@section('title', 'تفاصيل الدور - ' . $role->role_name . ' - Noon Care')

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
                        @if (request()->user()->hasPermission('roles.update'))
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary ms-2">
                                <i class="bi bi-pencil"></i> تعديل
                            </a>
                        @endif
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-person-badge"></i> تفاصيل الدور: {{ $role->role_name }}
                    </h4>
                </div>

                <!-- تفاصيل الدور -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">معلومات أساسية</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>اسم الدور:</strong>
                                    <p>{{ $role->role_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>الوصف:</strong>
                                    <p>{{ $role->description ?? 'لا يوجد وصف' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>الحالة:</strong>
                                    <p>
                                        <span class="badge bg-{{ $role->is_default ? 'success' : 'secondary' }}">
                                            {{ $role->is_default ? 'افتراضي' : 'مخصص' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>تاريخ الإنشاء:</strong>
                                    <p>{{ $role->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>آخر تحديث:</strong>
                                    <p>{{ $role->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">المسؤولون المرتبطون</h5>
                            </div>
                            <div class="card-body">
                                @if ($role->admins->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم المسؤول</th>
                                                    <th>البريد الإلكتروني</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($role->admins as $admin)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $admin->name }}</td>
                                                        <td>{{ $admin->email }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center">لا يوجد مسؤولون مرتبطون بهذا الدور</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الصلاحيات -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">صلاحيات الدور</h5>
                    </div>
                    <div class="card-body">
                        @if ($role->permissions->count() > 0)
                            <div class="row">
                                @foreach ($groupedPermissions as $module => $permissions)
                                    <div class="col-md-6 mb-4">
                                        <h6 class="border-bottom pb-2">{{ $module }}</h6>
                                        <ul class="list-group">
                                            @foreach ($permissions as $permission)
                                                @if ($role->permissions->contains('id', $permission->id))
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $permission->permission_name }}
                                                        <span class="badge bg-success">ممنوح</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center">لا توجد صلاحيات مرتبطة بهذا الدور</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
