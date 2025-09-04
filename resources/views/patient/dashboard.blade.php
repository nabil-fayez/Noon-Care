@extends('layouts.app')

@section('title', 'لوحة تحكم المريض - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('patient.partials.sidebar')
            </div>

            <div class="col-md-9">
                <!-- إحصائيات سريعة -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>المواعيد القادمة</h5>
                                        <h3>{{ $upcomingAppointments }}</h3>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-calendar-check display-4 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>المواعيد المنتهية</h5>
                                        <h3>{{ $completedAppointments }}</h3>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-calendar-week display-4 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>السجلات الطبية</h5>
                                        <h3>{{ $medicalRecordsCount }}</h3>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-file-medical display-4 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المواعيد القادمة -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">مواعيدي القادمة</h5>
                        <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($recentAppointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>الطبيب</th>
                                            <th>التاريخ</th>
                                            <th>الوقت</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentAppointments as $appointment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $appointment->doctor->profile_image ?? 'https://via.placeholder.com/40' }}"
                                                            class="rounded-circle me-2" width="40" height="40">
                                                        <div>
                                                            <strong>{{ $appointment->doctor->full_name }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                @foreach ($appointment->doctor->specialties as $specialty)
                                                                    <span
                                                                        class="badge bg-info">{{ $specialty->name }}</span>
                                                                @endforeach
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $appointment->appointment_datetime->format('Y-m-d') }}</td>
                                                <td>{{ $appointment->appointment_datetime->format('h:i A') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('patient.appointment.show', $appointment) }}"
                                                        class="btn btn-sm btn-info">تفاصيل</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x display-4"></i>
                                <p class="mt-3">لا توجد مواعيد قادمة</p>
                                <a href="{{ route('patient.appointment.create') }}" class="btn btn-primary">
                                    حجز موعد جديد
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- السجلات الطبية الحديثة -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">آخر السجلات الطبية</h5>
                        <a href="{{ route('patient.medicalHistory') }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($recentMedicalRecords->count() > 0)
                            <div class="row">
                                @foreach ($recentMedicalRecords as $record)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $record->title }}</h6>
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($record->description, 100) }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        {{ $record->doctor->full_name }}
                                                    </small>
                                                    <span
                                                        class="badge bg-{{ $record->is_urgent ? 'danger' : 'secondary' }}">
                                                        {{ $record->record_type }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent">
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">
                                                        {{ $record->record_date->format('Y-m-d') }}
                                                    </small>
                                                    <a href="{{ route('medical_record.show', $record) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        عرض التفاصيل
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-file-medical display-4"></i>
                                <p class="mt-3">لا توجد سجلات طبية</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
