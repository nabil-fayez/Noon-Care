@extends('layouts.admin')

@section('title', 'إدارة المنشآت الطبية - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-building"></i> قائمة المنشآت الطبية
                            <span class="badge bg-primary">{{ $facilities->total() }}</span>
                        </h5>
                        <a href="{{ route('admin.facility.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة منشأة جديدة
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.facilities.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="بحث بالاسم أو العنوان" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير
                                            نشط</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">إعادة
                                        تعيين</a>
                                </div>
                            </div>
                        </form>

                        <!-- جدول المنشآت -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>الشعار</th>
                                        <th>اسم المنشأة</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الهاتف</th>
                                        <th>العنوان</th>
                                        <th>عدد الأطباء</th>
                                        <th>الحالة</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($facilities as $facility)
                                        <tr>
                                            <td>
                                                <img src="{{ $facility->logo_url ?? 'https://via.placeholder.com/50' }}"
                                                    class="rounded" width="50" height="50" alt="شعار المنشأة">
                                            </td>
                                            <td>
                                                <strong>{{ $facility->business_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $facility->username }}</small>
                                            </td>
                                            <td>{{ $facility->email ?? 'غير متوفر' }}</td>
                                            <td>{{ $facility->phone ?? 'غير متوفر' }}</td>
                                            <td>{{ Str::limit($facility->address, 30) ?? 'غير متوفر' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $facility->doctors_count }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $facility->is_active ? 'success' : 'secondary' }}">
                                                    {{ $facility->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </td>
                                            <td>{{ $facility->created_at->format('Y-m-d') }}</td>
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

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
            transition: background-color 0.2s ease;
        }

        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
@endpush
