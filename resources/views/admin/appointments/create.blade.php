@extends('layouts.admin')

@section('title', 'إضافة حجز جديد - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>إضافة حجز جديد</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.appointments.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient_id">المريض *</label>
                                        <select name="patient_id" id="patient_id" class="form-select" required>
                                            <option value="">اختر المريض</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                    {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                    {{ $patient->first_name }} {{ $patient->last_name }} -
                                                    {{ $patient->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">الطبيب *</label>
                                        <select name="doctor_id" id="doctor_id" class="form-select" required>
                                            <option value="">اختر الطبيب</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facility_id">المنشأة *</label>
                                        <select name="facility_id" id="facility_id" class="form-select" required>
                                            <option value="">اختر المنشأة</option>
                                            @foreach ($facilities as $facility)
                                                <option value="{{ $facility->id }}"
                                                    {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                                    {{ $facility->business_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_id">الخدمة *</label>
                                        <select name="service_id" id="service_id" class="form-select" required>
                                            <option value="">اختر الخدمة</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="appointment_datetime">التاريخ والوقت *</label>
                                        <input type="datetime-local" name="appointment_datetime" id="appointment_datetime"
                                            class="form-control" value="{{ old('appointment_datetime') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duration">المدة (دقيقة) *</label>
                                        <input type="number" name="duration" id="duration" class="form-control"
                                            value="{{ old('duration', 30) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">الحالة *</label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>جديد
                                            </option>
                                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>
                                                مؤكد</option>
                                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                                ملغي</option>
                                            <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>منتهي
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="insurance_company_id">شركة التأمين</label>
                                        <select name="insurance_company_id" id="insurance_company_id" class="form-select">
                                            <option value="">بدون تأمين</option>
                                            @foreach ($insuranceCompanies as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ old('insurance_company_id') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">السعر (ريال سعودي)</label>
                                        <input type="number" name="price" id="price" class="form-control"
                                            value="{{ old('price') }}" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">حفظ الحجز</button>
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
