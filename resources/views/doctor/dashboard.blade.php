@extends('layouts.app')

@section('title', 'لوحة تحكم الطبيب - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('doctor.partials.sidebar')
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>المواعيد اليوم</h5>
                                        <h3>{{ $todayAppointments }}</h3>
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
                                        <h5>المواعيد هذا الأسبوع</h5>
                                        <h3>{{ $weekAppointments }}</h3>
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
                                        <h5>متوسط التقييم</h5>
                                        <h3>{{ number_format($averageRating, 1) }}/5</h3>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-star-fill display-4 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">مواعيد اليوم</h5>
                    </div>
                    <div class="card-body">
                        @if ($todayAppointmentsList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>المريض</th>
                                            <th>الوقت</th>
                                            <th>الخدمة</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($todayAppointmentsList as $appointment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $appointment->patient->profile_image_url ?? 'https://via.placeholder.com/40' }}"
                                                            class="rounded-circle me-2" width="40" height="40">
                                                        <div>
                                                            <strong>{{ $appointment->patient->full_name }}</strong>
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $appointment->patient->phone }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $appointment->appointment_datetime->format('h:i A') }}</td>
                                                <td>{{ $appointment->service->name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#appointmentModal"
                                                        data-appointment-id="{{ $appointment->id }}">
                                                        تفاصيل
                                                    </button>
                                                    @if ($appointment->status === 'confirmed')
                                                        <form
                                                            action="{{ route('doctor.appointment.complete', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                اكتمال
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x display-4"></i>
                                <p class="mt-3">لا توجد مواعيد لليوم</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">آخر التقييمات</h5>
                            </div>
                            <div class="card-body">
                                @if ($recentReviews->count() > 0)
                                    @foreach ($recentReviews as $review)
                                        <div class="border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>{{ $review->patient->full_name }}</strong>
                                                <div class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if ($review->comment)
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            @endif
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center text-muted">لا توجد تقييمات بعد</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">الإحصائيات</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="statsChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for appointment details -->
    <div class="modal fade" id="appointmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل الموعد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="appointmentDetails">
                        <!-- سيتم تحميل التفاصيل هنا عبر AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // مخطط الإحصائيات
            const ctx = document.getElementById('statsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['مؤكدة', 'منتهية', 'ملغاة'],
                    datasets: [{
                        data: [{{ $confirmedAppointments }}, {{ $completedAppointments }},
                            {{ $cancelledAppointments }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 99, 132, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
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

            // تحميل تفاصيل الموعد
            $('#appointmentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var appointmentId = button.data('appointment-id');

                $.get('/doctor/appointments/' + appointmentId, function(data) {
                    $('#appointmentDetails').html(data);
                });
            });
        </script>
    @endpush

@endsection
