@extends('layouts.admin')

@section('title', 'إدارة المسؤولين - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-people"></i> إدارة المسؤولين
                    </h4>
                    @if (request()->user()->hasPermission('admins.create'))
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة مسؤول جديد
                        </a>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة المسؤولين</h5>
                    </div>
                    <div class="card-body">
                        <!-- شريط البحث والتصفية -->
                        <form method="GET" action="{{ route('admin.admins.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                                            value="{{ request('search') }}">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            معطل</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary w-100">
                                        <i class="bi bi-arrow-clockwise"></i> إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الدور</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $admin)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $admin->role->role_name ?? 'بدون دور' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                                    {{ $admin->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </td>
                                            <td>{{ $admin->created_at->format('Y/m/d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">

                                                    @if (request()->user()->hasPermission('admins.view'))
                                                        <a href="{{ route('admin.admins.show', $admin) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if (request()->user()->hasPermission('admins.update'))
                                                        <a href="{{ route('admin.admins.edit', $admin) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        @if ($admin->id !== auth('admin')->id())
                                                            <form action="{{ route('admin.admins.toggleStatus', $admin) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-{{ $admin->is_active ? 'warning' : 'success' }}">
                                                                    <i
                                                                        class="bi bi-{{ $admin->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                    @if (request()->user()->hasPermission('admins.delete'))
                                                        @if ($admin->id !== auth('admin')->id())
                                                            <a href="{{ route('admin.admins.delete', $admin) }}"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">لا توجد مسؤولين مضافة</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $admins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
