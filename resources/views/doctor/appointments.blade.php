@extends('layouts.app')

@section('title', 'إدارة المواعيد')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar similar to patient dashboard -->
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>جدول المواعيد</h5>
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
                                                    {{ $appointment->patient->name }}
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل الموعد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Appointment details will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('datePicker').addEventListener('change', function() {
                window.location.href = '{{ route('doctor.appointments') }}?date=' + this.value;
            });

            $('#appointmentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var appointmentId = button.data('appointment-id');

                $.get('/doctor/appointments/' + appointmentId, function(data) {
                    $('#appointmentModal .modal-body').html(data);
                });
            });
        </script>
    @endpush
@endsection
