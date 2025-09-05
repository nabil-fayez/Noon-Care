@extends('layouts.admin')

@section('title', 'إضافة طبيب إلى المنشأة - ' . $facility->business_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.facility.doctors', $facility) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى أطباء المنشأة
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus"></i> إضافة طبيب جديد
                    </h4>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">إضافة طبيب إلى: {{ $facility->business_name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.facility.storeDoctor', $facility) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="doctor_id" class="form-label">اختر الطبيب</label>
                                <select class="form-select" id="doctor_id" name="doctor_id" required>
                                    <option value="">اختر الطبيب</option>
                                    @foreach ($availableDoctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->full_name }} -
                                            {{ $doctor->specialties->pluck('name')->implode(', ') }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">نشط</option>
                                    <option value="pending">قيد الانتظار</option>
                                    <option value="inactive">غير نشط</option>
                                </select>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="available_for_appointments"
                                    name="available_for_appointments" value="1" checked>
                                <label class="form-check-label" for="available_for_appointments">
                                    متاح لحجز المواعيد
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">إضافة الطبيب</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#doctor_id').select2({
                placeholder: "اختر الطبيب",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
