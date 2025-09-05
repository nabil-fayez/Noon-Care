@extends('layouts.admin')

@section('title', 'إدارة الأدوار - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">

                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-person-badge"></i> إدارة الأدوار
                    </h4>
                    @if (request()->user()->hasPermission('roles.create'))
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إنشاء دور جديد
                        </a>
                    @endif
                </div>

                <!-- جدول الأدوار -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة الأدوار</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الدور</th>
                                        <th>الوصف</th>
                                        <th>عدد المسؤولين</th>
                                        <th>عدد الصلاحيات</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $role->role_name }}</td>
                                            <td>{{ $role->description ?? 'لا يوجد وصف' }}</td>
                                            <td>{{ $role->admins_count }}</td>
                                            <td>{{ $role->permissions_count }}</td>
                                            <td>
                                                <span class="badge bg-{{ $role->is_default ? 'success' : 'secondary' }}">
                                                    {{ $role->is_default ? 'افتراضي' : 'مخصص' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if (request()->user()->hasPermission('roles.view'))
                                                        <a href="{{ route('admin.roles.show', $role) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if (request()->user()->hasPermission('roles.update'))
                                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                    @if (request()->user()->hasPermission('roles.delete'))
                                                        @if ($role->admins_count == 0 && !$role->is_default)
                                                            <form action="{{ route('admin.roles.destroy', $role) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">لا توجد أدوار مضافة</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
