@extends('layouts.app')

@section('title', $facility->business_name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ $facility->logo ? Storage::url($facility->logo) : 'https://avatar.iran.liara.run/public/36' }}"
                            class="rounded mb-3" width="200" height="200">
                        <h3>{{ $facility->business_name }}</h3>
                        <p class="text-muted">{{ $facility->address }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @if ($facility->phone)
                                <a href="tel:{{ $facility->phone }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-telephone"></i> {{ $facility->phone }}
                                </a>
                            @endif
                            @if ($facility->website)
                                <a href="{{ $facility->website }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-globe"></i> الموقع
                                </a>
                            @endif
                        </div>

                        @if ($facility->description)
                            <div class="border-top pt-3">
                                <h5>عن المنشأة</h5>
                                <p>{{ $facility->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">الخدمات المتاحة</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($facility->services as $service)
                                <div class="col-6 mb-2">
                                    <span class="badge bg-success">{{ $service->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>الأطباء المتاحين</h4>
                    </div>
                    <div class="card-body">
                        @if ($facility->doctors->count() > 0)
                            <div class="row">
                                @foreach ($facility->doctors as $doctor)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $doctor->profile_image ? Storage::url($doctor->profile_image) : 'https://via.placeholder.com/60' }}"
                                                        class="rounded-circle me-3" width="60" height="60">
                                                    <div>
                                                        <h5 class="mb-1">{{ $doctor->name }}</h5>
                                                        <p class="text-muted mb-1">
                                                            @foreach ($doctor->specialties as $specialty)
                                                                <span class="badge bg-info">{{ $specialty->name }}</span>
                                                            @endforeach
                                                        </p>
                                                        @if ($doctor->pivot->status === 'active')
                                                            <span class="badge bg-success">متاح</span>
                                                        @else
                                                            <span class="badge bg-secondary">غير متاح</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if ($doctor->bio)
                                                    <p class="mt-3">{{ Str::limit($doctor->bio, 100) }}</p>
                                                @endif

                                                <div class="mt-3">
                                                    <a href="{{ route('doctors.show', $doctor) }}"
                                                        class="btn btn-sm btn-outline-primary">عرض الملف</a>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#bookModal" data-doctor-id="{{ $doctor->id }}"
                                                        data-doctor-name="{{ $doctor->name }}">
                                                        حجز موعد
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-person-x display-4"></i>
                                <p class="mt-3">لا يوجد أطباء متاحين حالياً</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($facility->latitude && $facility->longitude)
                    <div class="card mt-3">
                        <div class="card-header">الموقع على الخريطة</div>
                        <div class="card-body">
                            <div id="map" style="height: 300px;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for booking -->
    <div class="modal fade" id="bookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">حجز موعد مع <span id="doctorName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm" method="POST" action="{{ route('appointments.store') }}">
                        @csrf
                        <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                        <input type="hidden" name="doctor_id" id="doctorId">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">الخدمة</label>
                                    <select class="form-select" name="service_id" required>
                                        <option value="">اختر الخدمة</option>
                                        @foreach ($facility->services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insurance_company_id" class="form-label">شركة التأمين (اختياري)</label>
                                    <select class="form-select" name="insurance_company_id">
                                        <option value="">بدون تأمين</option>
                                        @foreach ($insuranceCompanies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">التاريخ</label>
                                    <input type="date" class="form-control" name="date" id="appointmentDate"
                                        required min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="time" class="form-label">الوقت</label>
                                    <select class="form-select" name="time" id="timeSlots" required>
                                        <option value="">اختر الوقت</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">تأكيد الحجز</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if ($facility->latitude && $facility->longitude)
            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
            <script>
                var map = L.map('map').setView([{{ $facility->latitude }}, {{ $facility->longitude }}], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([{{ $facility->latitude }}, {{ $facility->longitude }}])
                    .addTo(map)
                    .bindPopup('{{ $facility->business_name }}')
                    .openPopup();
            </script>
        @endif

        <script>
            $('#bookModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var doctorId = button.data('doctor-id');
                var doctorName = button.data('doctor-name');

                $('#doctorName').text(doctorName);
                $('#doctorId').val(doctorId);
            });

            $('#appointmentDate').change(function() {
                var date = $(this).val();
                var doctorId = $('#doctorId').val();
                var facilityId = {{ $facility->id }};

                if (date && doctorId) {
                    $.get('/facilities/' + facilityId + '/slots', {
                        doctor_id: doctorId,
                        date: date
                    }, function(data) {
                        var timeSelect = $('#timeSlots');
                        timeSelect.empty().append('<option value="">اختر الوقت</option>');

                        $.each(data.slots, function(index, time) {
                            timeSelect.append('<option value="' + time + '">' + time + '</option>');
                        });
                    });
                }
            });
        </script>
    @endpush

@endsection
