@extends('layouts.admin')

@section('title', 'إدارة الأدوار')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>إدارة الأدوار</h4>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إنشاء دور جديد
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الدور</th>
                                        <th>الوصف</th>
                                        <th>عدد الصلاحيات</th>
                                        <th>عدد المسؤولين</th>
                                        <th>حالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->role_name }}</td>
                                            <td>{{ $role->description ?? 'لا يوجد وصف' }}</td>
                                            <td>{{ $role->permissions_count }}</td>
                                            <td>{{ $role->admins_count }}</td>
                                            <td>
                                                <span class="badge badge-{{ $role->is_default ? 'success' : 'secondary' }}">
                                                    {{ $role->is_default ? 'افتراضي' : 'مخصص' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.roles.show', $role->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
