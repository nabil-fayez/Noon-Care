@extends('layouts.admin')

@section('title', 'إدارة الحجوزات - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">قائمة الحجوزات</h5>
                        <div>
                            <a href="{{ route('admin.appointments.trashed') }}" class="btn btn-warning me-2">
                                <i class="bi bi-trash"></i> سلة المحذوفات
                            </a>
                            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> إضافة حجز جديد
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.appointments.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="بحث بالاسم أو الهاتف" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="doctor_id" class="form-select">
                                        <option value="">جميع الأطباء</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->first_name }} {{ $doctor->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="facility_id" class="form-select">
                                        <option value="">جميع المنشآت</option>
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}"
                                                {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                                {{ $facility->business_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد
                                        </option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            مؤكد</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            ملغي</option>
                                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>منتهي
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date" class="form-control" value="{{ request('date') }}"
                                        placeholder="التاريخ">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                </div>
                                <div class="col-md-1">
                                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">إعادة
                                        تعيين</a>
                                </div>
                            </div>
                        </form>

                        <!-- جدول الحجوزات -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>رقم الحجز</th>
                                        <th>المريض</th>
                                        <th>الطبيب</th>
                                        <th>المنشأة</th>
                                        <th>الخدمة</th>
                                        <th>التاريخ والوقت</th>
                                        <th>المدة</th>
                                        <th>الحالة</th>
                                        <th>السعر</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $appointment)
                                        <tr>
                                            <td>#{{ $appointment->id }}</td>
                                            <td>{{ $appointment->patient->first_name }}
                                                {{ $appointment->patient->last_name }}</td>
                                            <td>{{ $appointment->doctor->first_name }}
                                                {{ $appointment->doctor->last_name }}</td>
                                            <td>{{ $appointment->facility->business_name }}</td>
                                            <td>{{ $appointment->service->name ?? 'غير محدد' }}</td>
                                            <td>{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                            <td>{{ $appointment->duration }} دقيقة</td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status_color }}">
                                                    {{ $appointment->status_text }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->price ? number_format($appointment->price, 2) . ' ر.س' : 'غير محدد' }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                                        class="btn btn-sm btn-info">عرض</a>
                                                    <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                                        class="btn btn-sm btn-primary">تعديل</a>
                                                    <form action="{{ route('admin.appointments.destroy', $appointment) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-warning">حذف</button>
                                                    </form>
                                                    @if ($appointment->status != 'confirmed')
                                                        <form
                                                            action="{{ route('admin.appointments.updateStatus', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="confirmed">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success">تأكيد</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">لا توجد حجوزات</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- التصفح -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
