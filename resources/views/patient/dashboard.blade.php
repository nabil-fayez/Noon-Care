@extends('layouts.app')

@section('title', 'لوحة تحكم المريض')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">ملفي</div>
                    <div class="card-body text-center">
                        <img src="{{ auth()->user()->profile_image ?? 'https://via.placeholder.com/150' }}"
                            class="rounded-circle mb-3" width="100" height="100">
                        <h5>{{ auth()->user()->name }}</h5>
                        <p class="text-muted">مريض</p>
                    </div>
                </div>

                <div class="list-group mt-3">
                    <a href="{{ route('patient.dashboard') }}" class="list-group-item list-group-item-action active">
                        لوحة التحكم
                    </a>
                    <a href="{{ route('patient.appointments') }}" class="list-group-item list-group-item-action">
                        المواعيد
                    </a>
                    <a href="{{ route('patient.profile') }}" class="list-group-item list-group-item-action">
                        الملف الشخصي
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>المواعيد القادمة</h5>
                                <h3>{{ $upcomingAppointments }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>المواعيد المنتهية</h5>
                                <h3>{{ $completedAppointments }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5>التقييمات</h5>
                                <h3>{{ $reviewsCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5>مواعيدي القادمة</h5>
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
                                                <td>{{ $appointment->doctor->name }}</td>
                                                <td>{{ $appointment->appointment_datetime->format('Y-m-d') }}</td>
                                                <td>{{ $appointment->appointment_datetime->format('H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('appointments.show', $appointment) }}"
                                                        class="btn btn-sm btn-info">عرض</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">لا توجد مواعيد قادمة</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
