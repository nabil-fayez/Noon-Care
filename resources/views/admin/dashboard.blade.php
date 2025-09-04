@extends('layouts.admin')

@section('title', 'لوحة تحكم الإدارة')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <h5>إجمالي الأطباء</h5>
                                <h2>{{ $stats['doctors'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body">
                                <h5>إجمالي المرضى</h5>
                                <h2>{{ $stats['patients'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body">
                                <h5>المواعيد اليوم</h5>
                                <h2>{{ $stats['today_appointments'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white mb-3">
                            <div class="card-body">
                                <h5>الإيرادات</h5>
                                <h2>{{ number_format($stats['revenue'], 2) }} ج.م</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
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
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">الإحصائيات</div>
                            <div class="card-body">
                                <canvas id="statsChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
