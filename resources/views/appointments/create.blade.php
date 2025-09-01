@extends('layouts.app')

@section('title', 'حجز موعد جديد')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">حجز موعد جديد</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('appointments.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">التخصص</label>
                                <div class="col-md-6">
                                    <select class="form-select" id="specialty" required>
                                        <option value="">اختر التخصص</option>
                                        @foreach ($specialties as $specialty)
                                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">الطبيب</label>
                                <div class="col-md-6">
                                    <select class="form-select" id="doctor" name="doctor_id" required>
                                        <option value="">اختر الطبيب</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">التاريخ</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="date" name="date" required
                                        min="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">الوقت</label>
                                <div class="col-md-6">
                                    <select class="form-select" id="time" name="time" required>
                                        <option value="">اختر الوقت</option>
                                        <!-- Times will be populated via JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">الخدمة</label>
                                <div class="col-md-6">
                                    <select class="form-select" name="service_id" required>
                                        <option value="">اختر الخدمة</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        تأكيد الحجز
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('specialty').addEventListener('change', function() {
                const specialtyId = this.value;
                fetch(`/api/doctors?specialty_id=${specialtyId}`)
                    .then(response => response.json())
                    .then(doctors => {
                        const doctorSelect = document.getElementById('doctor');
                        doctorSelect.innerHTML = '<option value="">اختر الطبيب</option>';
                        doctors.forEach(doctor => {
                            doctorSelect.innerHTML +=
                            `<option value="${doctor.id}">${doctor.name}</option>`;
                        });
                    });
            });

            document.getElementById('doctor').addEventListener('change', function() {
                const doctorId = this.value;
                const date = document.getElementById('date').value;
                if (date) {
                    fetchAvailableTimes(doctorId, date);
                }
            });

            document.getElementById('date').addEventListener('change', function() {
                const doctorId = document.getElementById('doctor').value;
                const date = this.value;
                if (doctorId && date) {
                    fetchAvailableTimes(doctorId, date);
                }
            });

            function fetchAvailableTimes(doctorId, date) {
                fetch(`/api/doctors/${doctorId}/availability?date=${date}`)
                    .then(response => response.json())
                    .then(times => {
                        const timeSelect = document.getElementById('time');
                        timeSelect.innerHTML = '<option value="">اختر الوقت</option>';
                        times.forEach(time => {
                            timeSelect.innerHTML += `<option value="${time}">${time}</option>`;
                        });
                    });
            }
        </script>
    @endpush
@endsection
