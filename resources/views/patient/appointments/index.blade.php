@extends('layouts.app')

@section('title', 'مواعيدي - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('patient.partials.sidebar')
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">مواعيدي</h5>
                        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> حجز موعد جديد
                        </a>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs" id="appointmentsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab"
                                    data-bs-target="#upcoming" type="button" role="tab">
                                    القادمة <span class="badge bg-primary">{{ $upcomingAppointments->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past"
                                    type="button" role="tab">
                                    المنتهية <span class="badge bg-secondary">{{ $pastAppointments->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled"
                                    type="button" role="tab">
                                    الملغاة <span class="badge bg-danger">{{ $cancelledAppointments->count() }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="appointmentsTabContent">
                            <!-- المواعيد القادمة -->
                            <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                                @if ($upcomingAppointments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>الطبيب</th>
                                                    <th>المنشأة</th>
                                                    <th>الخدمة</th>
                                                    <th>التاريخ والوقت</th>
                                                    <th>الحالة</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($upcomingAppointments as $appointment)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $appointment->doctor->profile_image_url }}"
                                                                    class="rounded-circle me-2" width="40"
                                                                    height="40">
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
                                                        <td>{{ $appointment->facility->business_name }}</td>
                                                        <td>{{ $appointment->service->name }}</td>
                                                        <td>
                                                            {{ $appointment->appointment_datetime->format('Y-m-d') }}
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ $appointment->appointment_datetime->format('h:i A') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $appointment->status_color }}">
                                                                {{ $appointment->status_text }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                                class="btn btn-sm btn-info">تفاصيل</a>
                                                            @if ($appointment->status === 'confirmed')
                                                                <form
                                                                    action="{{ route('appointments.cancel', $appointment) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                                        onclick="return confirm('هل أنت متأكد من إلغاء الموعد؟')">
                                                                        إلغاء
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
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x display-4"></i>
                                        <p class="mt-3">لا توجد مواعيد قادمة</p>
                                    </div>
                                @endif
                            </div>

                            <!-- المواعيد المنتهية -->
                            <div class="tab-pane fade" id="past" role="tabpanel">
                                @if ($pastAppointments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>الطبيب</th>
                                                    <th>المنشأة</th>
                                                    <th>الخدمة</th>
                                                    <th>التاريخ</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pastAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->doctor->full_name }}</td>
                                                        <td>{{ $appointment->facility->business_name }}</td>
                                                        <td>{{ $appointment->service->name }}</td>
                                                        <td>{{ $appointment->appointment_datetime->format('Y-m-d') }}</td>
                                                        <td>
                                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                                class="btn btn-sm btn-info">تفاصيل</a>
                                                            @if (!$appointment->review)
                                                                <button class="btn btn-sm btn-success"
                                                                    data-bs-toggle="modal" data-bs-target="#reviewModal"
                                                                    data-appointment-id="{{ $appointment->id }}">
                                                                    تقييم
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-check display-4"></i>
                                        <p class="mt-3">لا توجد مواعيد منتهية</p>
                                    </div>
                                @endif
                            </div>

                            <!-- المواعيد الملغاة -->
                            <div class="tab-pane fade" id="cancelled" role="tabpanel">
                                @if ($cancelledAppointments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>الطبيب</th>
                                                    <th>المنشأة</th>
                                                    <th>الخدمة</th>
                                                    <th>التاريخ</th>
                                                    <th>سبب الإلغاء</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cancelledAppointments as $appointment)
                                                    <tr>
                                                        <td>{{ $appointment->doctor->full_name }}</td>
                                                        <td>{{ $appointment->facility->business_name }}</td>
                                                        <td>{{ $appointment->service->name }}</td>
                                                        <td>{{ $appointment->appointment_datetime->format('Y-m-d') }}</td>
                                                        <td>{{ $appointment->cancellation_reason ?? 'لم يتم تحديد سبب' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x display-4"></i>
                                        <p class="mt-3">لا توجد مواعيد ملغاة</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for reviews -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تقييم الموعد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" name="appointment_id" id="reviewAppointmentId">

                        <div class="mb-3">
                            <label class="form-label">التقييم</label>
                            <div class="rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating"
                                        value="{{ $i }}" required>
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">التعليق (اختياري)</label>
                            <textarea class="form-control" name="comment" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: flex-end;
            }

            .rating input {
                display: none;
            }

            .rating label {
                cursor: pointer;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: #ccc;
            }

            .rating input:checked~label {
                color: #ffc107;
            }

            .rating label:hover,
            .rating label:hover~label {
                color: #ffc107;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $('#reviewModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var appointmentId = button.data('appointment-id');
                $('#reviewAppointmentId').val(appointmentId);
            });
        </script>
    @endpush

@endsection
