@extends('layouts.admin')

@section('title', 'إدارة الأطباء - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">قائمة الأطباء</h5>
                        <div>
                            <a href="{{ route('admin.doctors.trashed') }}" class="btn btn-warning me-2">
                                <i class="bi bi-trash"></i> سلة المحذوفات
                            </a>
                            <a href="{{ route('admin.doctor.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> إضافة طبيب جديد
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.doctors.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="بحث بالاسم أو البريد" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="specialty_id" class="form-select">
                                        <option value="">جميع التخصصات</option>
                                        @foreach ($specialties as $specialty)
                                            <option value="{{ $specialty->id }}"
                                                {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="is_verified" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>موثق
                                        </option>
                                        <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>غير
                                            موثق</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                                </div>
                            </div>
                        </form>

                        <!-- جدول الأطباء -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الصورة</th>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الهاتف</th>
                                        <th>التخصصات</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctors as $doctor)
                                        <tr>
                                            <td>
                                                <img src="{{ $doctor->profile_image ?? 'https://via.placeholder.com/50' }}"
                                                    class="rounded-circle" width="50" height="50" alt="صورة الطبيب">
                                            </td>
                                            <td>{{ $doctor->full_name }}</td>
                                            <td>{{ $doctor->email }}</td>
                                            <td>{{ $doctor->phone ?? 'غير متوفر' }}</td>
                                            <td>
                                                @foreach ($doctor->specialties as $specialty)
                                                    <span class="badge bg-info">{{ $specialty->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $doctor->is_verified ? 'success' : 'warning' }}">
                                                    {{ $doctor->is_verified ? 'موثق' : 'غير موثق' }}
                                                </span>
                                            </td>
                                            <td>{{ $doctor->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.doctor.show', $doctor) }}"
                                                        class="btn btn-sm btn-info">عرض</a>
                                                    <a href="{{ route('admin.doctor.edit', $doctor) }}"
                                                        class="btn btn-sm btn-primary">تعديل</a>
                                                    <a href="{{ route('admin.doctor.delete', $doctor) }}"
                                                        class="btn btn-sm btn-warning">حذف</a>
                                                    <form action="{{ route('admin.doctor.toggleVerification', $doctor) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $doctor->is_verified ? 'warning' : 'success' }}">
                                                            {{ $doctor->is_verified ? 'إلغاء التوثيق' : 'توثيق' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">لا توجد بيانات</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- التصفح -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $doctors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
