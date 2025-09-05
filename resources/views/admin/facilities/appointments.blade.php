@extends('layouts.admin')

@section('title', 'مواعيد المنشأة - ' . $facility->business_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.facility.show', $facility) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى المنشأة
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-check"></i> مواعيد المنشأة: {{ $facility->business_name }}
                    </h4>
                    <a href="#" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> حجز موعد جديد
                    </a>
                </div>

                <!-- فلترة المواعيد -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.facility.appointments', $facility) }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">حالة الموعد</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">جميع الحالات</option>
                                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد
                                        </option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            مؤكد</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            ملغى</option>
                                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>منتهي
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">من تاريخ</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="date_to" class="form-label">إلى تاريخ</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.facility.appointments', $facility) }}"
                                        class="btn btn-secondary">إعادة تعيين</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- جدول المواعيد -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة المواعيد</h5>
                    </div>
                    <div class="card-body">
                        @if ($appointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>المريض</th>
                                            <th>الطبيب</th>
                                            <th>الخدمة</th>
                                            <th>التاريخ والوقت</th>
                                            <th>المدة</th>
                                            <th>الحالة</th>
                                            <th>السعر</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($appointments as $appointment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $appointment->patient->full_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->patient->phone }}</small>
                                                </td>
                                                <td>{{ $appointment->doctor->full_name }}</td>
                                                <td>{{ $appointment->service->name }}</td>
                                                <td>{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                                <td>{{ $appointment->duration }} دقيقة</td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($appointment->price, 2) }} ج.م</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.appointment.show', $appointment) }}"
                                                            class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.appointment.edit', $appointment) }}"
                                                            class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- التصفح -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $appointments->links() }}
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x display-4"></i>
                                <h4 class="mt-3">لا توجد مواعيد في هذه المنشأة</h4>
                                <p class="mb-4">لم يتم حجز أي مواعيد في هذه المنشأة حتى الآن</p>
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
        .badge.bg-new {
            background-color: #6c757d;
        }

        .badge.bg-confirmed {
            background-color: #198754;
        }

        .badge.bg-cancelled {
            background-color: #dc3545;
        }

        .badge.bg-done {
            background-color: #0dcaf0;
        }
    </style>
@endpush
