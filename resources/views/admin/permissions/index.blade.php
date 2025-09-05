@extends('layouts.admin')

@section('title', 'إدارة الصلاحيات - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">


                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock"></i> إدارة الصلاحيات
                    </h4>
                    @if (request()->user()->hasPermission('permissions.create'))
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إنشاء صلاحية جديدة
                        </a>
                    @endif
                </div>

                <!-- جدول الصلاحيات -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة الصلاحيات</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($groupedPermissions as $module => $permissions)
                            <div class="module-group mb-5">
                                <h5 class="mb-3 border-bottom pb-2">{{ $module }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>اسم الصلاحية</th>
                                                <th>الوصف</th>
                                                <th>عدد الأدوار</th>
                                                <th>تاريخ الإنشاء</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $permission)
                                                <tr>
                                                    <td>{{ $permission->permission_name }}</td>
                                                    <td>{{ $permission->description ?? 'لا يوجد وصف' }}</td>
                                                    <td>{{ $permission->roles->count() }}</td>
                                                    <td>{{ $permission->created_at->format('Y/m/d') }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            @if (request()->user()->hasPermission('permissions.view'))
                                                                <a href="{{ route('admin.permissions.show', $permission) }}"
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            @endif
                                                            @if (request()->user()->hasPermission('permission.update'))
                                                                <a href="{{ route('admin.permissions.edit', $permission) }}"
                                                                    class="btn btn-sm btn-primary">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                            @endif
                                                            @if (request()->user()->hasPermission('permissions.delete'))
                                                                @if ($permission->roles_count == 0)
                                                                    <form
                                                                        action="{{ route('admin.permissions.destroy', $permission) }}"
                                                                        method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                            onclick="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
