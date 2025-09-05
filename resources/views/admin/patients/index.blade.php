@extends('layouts.admin')

@section('title', 'إدارة المرضى - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-people"></i> قائمة المرضى
                            <span class="badge bg-primary">{{ $patients->total() }}</span>
                        </h5>
                        <a href="{{ route('admin.patient.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة مريض جديد
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.patients.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="بحث بالاسم أو البريد" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="gender" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر
                                        </option>
                                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى
                                        </option>
                                    </select>
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
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                                </div>
                            </div>
                        </form>

                        <!-- جدول المرضى -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>الصورة</th>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الهاتف</th>
                                        <th>العمر</th>
                                        <th>النوع</th>
                                        <th>الحالة</th>
                                        <th>تاريخ التسجيل</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patients as $patient)
                                        <tr>
                                            <td>
                                                <img src="{{ $patient->profile_image_url }}"
                                                    class="rounded-circle" width="50" height="50" alt="صورة المريض">
                                            </td>
                                            <td>
                                                <strong>{{ $patient->full_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $patient->username }}</small>
                                            </td>
                                            <td>{{ $patient->email }}</td>
                                            <td>{{ $patient->phone ?? 'غير متوفر' }}</td>
                                            <td>
                                                @if ($patient->date_of_birth)
                                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} سنة
                                                @else
                                                    غير محدد
                                                @endif
                                            </td>
                                            <td>
                                                @if ($patient->gender == 'male')
                                                    <span class="badge bg-info">ذكر</span>
                                                @elseif($patient->gender == 'female')
                                                    <span class="badge bg-pink">أنثى</span>
                                                @else
                                                    <span class="badge bg-secondary">غير محدد</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }}">
                                                    {{ $patient->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </td>
                                            <td>{{ $patient->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.patient.show', $patient) }}"
                                                        class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.patient.edit', $patient) }}"
                                                        class="btn btn-sm btn-primary" title="تعديل">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.patient.toggleStatus', $patient) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $patient->is_active ? 'warning' : 'success' }}"
                                                            title="{{ $patient->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                            <i
                                                                class="bi bi-{{ $patient->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.patient.delete', $patient) }}"
                                                        class="btn btn-sm btn-danger" title="حذف">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="bi bi-people display-4 text-muted"></i>
                                                <p class="mt-3">لا توجد مرضى مسجلين</p>
                                                <a href="{{ route('admin.patient.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> إضافة أول مريض
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- التصفح -->
                        @if ($patients->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    عرض {{ $patients->firstItem() }} إلى {{ $patients->lastItem() }} من أصل
                                    {{ $patients->total() }} نتيجة
                                </div>
                                <div>
                                    {{ $patients->links() }}
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
        .bg-pink {
            background-color: #e83e8c !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
            transition: background-color 0.2s ease;
        }

        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
@endpush
