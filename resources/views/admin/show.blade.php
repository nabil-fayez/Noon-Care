@extends('layouts.admin')

@section('title', 'تفاصيل المسؤول - ' . $admin->name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة المسؤولين
                        </a>
                        @if (request()->user()->hasPermission('admins.update'))
                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary ms-2">
                                <i class="bi bi-pencil"></i> تعديل
                            </a>
                        @endif
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-person"></i> تفاصيل المسؤول: {{ $admin->name }}
                    </h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">معلومات أساسية</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>الاسم الكامل:</strong>
                                    <p>{{ $admin->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>البريد الإلكتروني:</strong>
                                    <p>{{ $admin->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>الدور:</strong>
                                    <p>
                                        <span class="badge bg-info">{{ $admin->role->role_name ?? 'بدون دور' }}</span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>الحالة:</strong>
                                    <p>
                                        <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                            {{ $admin->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>تاريخ الإنشاء:</strong>
                                    <p>{{ $admin->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>آخر تحديث:</strong>
                                    <p>{{ $admin->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">سجل النشاط</h5>
                            </div>
                            <div class="card-body">
                                @if ($admin->logs->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>النشاط</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($admin->logs->take(10) as $log)
                                                    <tr>
                                                        <td>{{ $log->action }}</td>
                                                        <td>{{ $log->created_at->format('Y/m/d H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center">لا توجد سجلات نشاط</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
