@extends('layouts.admin')

@section('title', 'إدارة المنشآت - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-building"></i> إدارة المنشآت
                        <span class="badge bg-primary">{{ $facilities->total() }}</span>
                    </h4>
                    @if (request()->user()->hasPermission('facilities.create'))
                        <a href="{{ route('admin.facility.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة منشأة جديدة
                        </a>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة المنشآت</h5>
                    </div>
                    <div class="card-body">
                        <!-- شريط البحث والتصفية -->
                        <form method="GET" action="{{ route('admin.facilities.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="ابحث باسم المنشأة أو البريد الإلكتروني..."
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
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الشعار</th>
                                        <th>اسم المنشأة</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الهاتف</th>
                                        <th>عدد الأطباء</th>
                                        <th>عدد الخدمات</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($facilities as $facility)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $facility->logo_url }}" class="rounded" width="50"
                                                    height="50" alt="شعار المنشأة">
                                            </td>
                                            <td>
                                                <strong>{{ $facility->business_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $facility->username }}</small>
                                            </td>
                                            <td>{{ $facility->email ?? 'غير متوفر' }}</td>
                                            <td>{{ $facility->phone ?? 'غير متوفر' }}</td>
                                            <td>{{ $facility->doctors_count }}</td>
                                            <td>{{ $facility->services_count }}</td>
                                            <td>
                                                <span class="badge bg-{{ $facility->is_active ? 'success' : 'danger' }}">
                                                    {{ $facility->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.facility.show', $facility) }}"
                                                        class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.facility.edit', $facility) }}"
                                                        class="btn btn-sm btn-primary" title="تعديل">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.facility.toggleStatus', $facility) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $facility->is_active ? 'warning' : 'success' }}"
                                                            title="{{ $facility->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                            <i
                                                                class="bi bi-{{ $facility->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.facility.delete', $facility) }}"
                                                        class="btn btn-sm btn-danger" title="حذف">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="bi bi-building display-4 text-muted"></i>
                                                <p class="mt-3">لا توجد منشآت طبية مسجلة</p>
                                                <a href="{{ route('admin.facility.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> إضافة أول منشأة
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- التصفح -->
                        @if ($facilities->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    عرض {{ $facilities->firstItem() }} إلى {{ $facilities->lastItem() }} من أصل
                                    {{ $facilities->total() }} نتيجة
                                </div>
                                <div>
                                    {{ $facilities->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
