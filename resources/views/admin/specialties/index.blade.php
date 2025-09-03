<!-- في resources/views/admin/specialties/index.blade.php -->
@extends('layouts.admin')

@section('title', 'إدارة التخصصات الطبية - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-tags"></i> قائمة التخصصات الطبية
                            <span class="badge bg-primary">{{ $specialties->total() }}</span>
                        </h5>
                        <a href="{{ route('admin.specialty.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة تخصص جديد
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.specialties.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="بحث باسم التخصص أو الوصف" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="is_active" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط
                                        </option>
                                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="order_by" class="form-select">
                                        <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>الاسم
                                        </option>
                                        <option value="created_at"
                                            {{ request('order_by') == 'created_at' ? 'selected' : '' }}>تاريخ الإضافة
                                        </option>
                                        <option value="doctors_count"
                                            {{ request('order_by') == 'doctors_count' ? 'selected' : '' }}>عدد الأطباء
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.specialties.index') }}" class="btn btn-secondary">إعادة
                                        تعيين</a>
                                </div>
                            </div>
                        </form>

                        @if ($specialties->count() > 0)
                            <!-- جدول التخصصات -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>الأيقونة</th>
                                            <th>اسم التخصص</th>
                                            <th>الوصف</th>
                                            <th>عدد الأطباء</th>
                                            <th>الحالة</th>
                                            <th>تاريخ الإنشاء</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($specialties as $specialty)
                                            <tr>
                                                <td>
                                                    @if ($specialty->icon_url)
                                                        <img src="{{ $specialty->icon_url }}" class="rounded"
                                                            width="40" height="40" alt="أيقونة التخصص">
                                                    @else
                                                        <div class="bg-{{ $specialty->color }} rounded d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="bi bi-heart-pulse text-white"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $specialty->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ $specialty->description ? Str::limit($specialty->description, 50) : 'لا يوجد وصف' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $specialty->doctors_count }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $specialty->is_active ? 'success' : 'secondary' }}">
                                                        {{ $specialty->is_active ? 'نشط' : 'غير نشط' }}
                                                    </span>
                                                </td>
                                                <td>{{ $specialty->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.specialty.show', $specialty) }}"
                                                            class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.specialty.edit', $specialty) }}"
                                                            class="btn btn-sm btn-primary" title="تعديل">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.specialty.toggleStatus', $specialty) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-{{ $specialty->is_active ? 'warning' : 'success' }}"
                                                                title="{{ $specialty->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                                <i
                                                                    class="bi bi-{{ $specialty->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                                            </button>
                                                        </form>
                                                        <a href="{{ route('admin.specialty.delete', $specialty) }}"
                                                            class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- التصفح -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    عرض {{ $specialties->firstItem() }} إلى {{ $specialties->lastItem() }} من أصل
                                    {{ $specialties->total() }} نتيجة
                                </div>
                                <div>
                                    {{ $specialties->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox display-4"></i>
                                <h4 class="mt-3">لا توجد تخصصات</h4>
                                <p class="mb-4">لم يتم إضافة أي تخصصات حتى الآن</p>
                                <a href="{{ route('admin.specialty.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> إضافة تخصص جديد
                                </a>
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
