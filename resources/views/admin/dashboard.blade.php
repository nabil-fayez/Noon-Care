@extends('layouts.admin')

@section('title', 'لوحة تحكم الإدارة')

@section('content')
    <div class="container-fluid">
        <div class="row col-md-12">
            @include('admin.partials.sidebar')
            <div class="col-md-10">
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted fw-semibold">إجمالي الأطباء</h6>
                                        <h4 class="fw-bold mb-0">{{ $stats['doctors'] }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-person-badge text-primary fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted fw-semibold">إجمالي المرضى</h6>
                                        <h4 class="fw-bold mb-0">{{ $stats['patients'] }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-people text-primary fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted fw-semibold">المواعيد اليوم</h6>
                                        <h4 class="fw-bold mb-0">{{ $stats['today_appointments'] }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-calendar-check text-primary fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted fw-semibold">المنشآت الطبية</h6>
                                        <h4 class="fw-bold mb-0">{{ $stats['facilities'] }}</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-building text-primary fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <div class="card">
                                <div class="card-header">احصائيات المستخدمين</div>
                                <div class="card-body">
                                    <canvas id="statsChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="card mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0">إحصائيات الحجوزات</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="appointmentStats" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                    <h5 class="mb-0">احدث المواعيد</h5>
                                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-primary">عرض
                                        الكل</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>المريض</th>
                                                    <th>الطبيب</th>
                                                    <th>التاريخ والوقت</th>
                                                    <th>الحالة</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recentAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->patient->full_name }}
                                                        </td>
                                                        <td>{{ $appointment->doctor->full_name }}
                                                        </td>
                                                        <td>{{ $appointment->appointment_datetime }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $appointment->status_color }}">
                                                                {{ $appointment->status_text }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href='{{ route('admin.appointments.show', $appointment) }}'
                                                                class="btn btn-sm btn-outline-primary">تفاصيل</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الإحصائيات الجانبية -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0">الأطباء المضافون حديثاً</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($recentDoctors as $doctor)
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $doctor->profile_image }}" class="rounded-circle me-3"
                                                alt="طبيب">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $doctor->full_name }}</h6>
                                                @foreach ($doctor->specialties as $specialty)
                                                    <span class="badge bg-info">{{ $specialty->name }}</span>
                                                @endforeach
                                            </div>
                                            <span class="badge bg-{{ $doctor->is_verified ? 'success' : 'warning' }}">
                                                {{ $doctor->is_verified ? 'موثق' : 'غير موثق' }}
                                            </span>
                                            <a href='{{ route('admin.doctor.show', $doctor) }}'
                                                class=" mx-3 btn btn-sm btn-outline-primary">تفاصيل</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">أحدث المواعيد</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>المريض</th>
                                            <th>الطبيب</th>
                                            <th>الحالة</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentAppointments as $appointment)
                                            <tr>
                                                <td>{{ $appointment->patient->first_name . ' ' . $appointment->patient->last_name }}
                                                </td>
                                                <td>{{ $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                </td>
                                                <td>{{ $appointment->appointment_datetime }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('appointmentStats').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['جديدة', 'مؤكدة', 'ملغية', 'مكتملة'],
                        datasets: [{
                            data: [
                                {{ $stats['total_new_appointments'] }},
                                {{ $stats['total_confirmed_appointments'] }},
                                {{ $stats['total_cancelled_appointments'] }},
                                {{ $stats['total_done_appointments'] }},
                            ],
                            backgroundColor: [
                                '#2c5aa0',
                                '#ffc107',
                                '#dc3545',
                                '#198754'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            });
        </script>
        <script>
            const ctx = document.getElementById('statsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['الأطباء', 'المرضى', 'المواعيد', 'الإيرادات'],
                    datasets: [{
                        label: 'الإحصائيات',
                        data: [
                            {{ $stats['doctors'] }},
                            {{ $stats['patients'] }},
                            {{ $stats['total_appointments'] }},
                            {{ $stats['revenue'] }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
