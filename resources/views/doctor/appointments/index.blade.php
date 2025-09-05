@extends('layouts.app')

@section('title', 'إدارة المواعيد - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('doctor.partials.sidebar')
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">جدول المواعيد</h5>
                        <div>
                            <input type="date" class="form-control" id="datePicker" value="{{ $selectedDate }}">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>الوقت</th>
                                        <th>المريض</th>
                                        <th>الخدمة</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timeSlots as $timeSlot)
                                        @php
                                            $appointment = $appointments->firstWhere('appointment_time', $timeSlot);
                                        @endphp
                                        <tr>
                                            <td>{{ $timeSlot }}</td>
                                            <td>
                                                @if ($appointment)
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $appointment->patient->profile_image_url }}"
                                                            class="rounded-circle me-2" width="40" height="40">
                                                        <div>
                                                            <strong>{{ $appointment->patient->full_name }}</strong>
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $appointment->patient->phone }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">فارغ</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($appointment)
                                                    {{ $appointment->service->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($appointment)
                                                    <span class="badge bg-{{ $appointment->status_color }}">
                                                        {{ $appointment->status_text }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">متاح</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($appointment)
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#appointmentModal"
                                                        data-appointment-id="{{ $appointment->id }}">
                                                        تفاصيل
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        <script>
            // تغيير التاريخ
            document.getElementById('datePicker').addEventListener('change', function() {
                window.location.href = '{{ route('doctor.appointments') }}?date=' + this.value;
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
