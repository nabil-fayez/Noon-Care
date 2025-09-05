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
                        <form action="{{ route('admin.appointments.store') }}" method="POST" id="appointmentForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient_id">المريض *</label>
                                        <select name="patient_id" id="patient_id" class="form-control"
                                            hx-get="{{ route('admin.apis.patients') }}"
                                            hx-trigger="focus, keyup delay:300ms changed" hx-target="#patients-results"
                                            hx-indicator="#patient-loading" required>
                                            <option value="">ابحث عن المريض...</option>
                                            @if (old('patient_id'))
                                                <option value="{{ old('patient_id') }}" selected>
                                                    {{ \App\Models\Patient::find(old('patient_id'))->full_name ?? 'تم التحديد' }}
                                                </option>
                                            @endif
                                        </select>
                                        <div id="patients-results" class="mt-2"></div>
                                        <div id="patient-loading" class="htmx-indicator mt-2">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">جاري التحميل...</span>
                                            </div>
                                            <span class="ms-2">جاري البحث عن المرضى...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">الطبيب *</label>
                                        <select name="doctor_id" id="doctor_id" class="form-control"
                                            hx-get="{{ route('admin.apis.doctors') }}"
                                            hx-trigger="focus, keyup delay:300ms changed" hx-target="#doctors-results"
                                            hx-indicator="#doctor-loading" required>
                                            <option value="">ابحث عن الطبيب...</option>
                                            @if (old('doctor_id'))
                                                <option value="{{ old('doctor_id') }}" selected>
                                                    {{ \App\Models\Doctor::find(old('doctor_id'))->full_name ?? 'تم التحديد' }}
                                                </option>
                                            @endif
                                        </select>
                                        <div id="doctors-results" class="mt-2"></div>
                                        <div id="doctor-loading" class="htmx-indicator mt-2">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">جاري التحميل...</span>
                                            </div>
                                            <span class="ms-2">جاري البحث عن الأطباء...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facility_id">المنشأة *</label>
                                        <select name="facility_id" id="facility_id" class="form-control"
                                            hx-get="{{ route('admin.apis.facilities') }}"
                                            hx-trigger="focus, keyup delay:300ms changed" hx-target="#facilities-results"
                                            hx-indicator="#facility-loading" required>
                                            <option value="">ابحث عن المنشأة...</option>
                                            @if (old('facility_id'))
                                                <option value="{{ old('facility_id') }}" selected>
                                                    {{ \App\Models\Facility::find(old('facility_id'))->business_name ?? 'تم التحديد' }}
                                                </option>
                                            @endif
                                        </select>
                                        <div id="facilities-results" class="mt-2"></div>
                                        <div id="facility-loading" class="htmx-indicator mt-2">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">جاري التحميل...</span>
                                            </div>
                                            <span class="ms-2">جاري البحث عن المنشآت...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_id">الخدمة *</label>
                                        <select name="service_id" id="service_id" class="form-control"
                                            hx-get="{{ route('admin.apis.services') }}"
                                            hx-trigger="focus, keyup delay:300ms changed" hx-target="#services-results"
                                            hx-indicator="#service-loading" required>
                                            <option value="">ابحث عن الخدمة...</option>
                                            @if (old('service_id'))
                                                <option value="{{ old('service_id') }}" selected>
                                                    {{ \App\Models\Service::find(old('service_id'))->name ?? 'تم التحديد' }}
                                                </option>
                                            @endif
                                        </select>
                                        <div id="services-results" class="mt-2"></div>
                                        <div id="service-loading" class="htmx-indicator mt-2">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">جاري التحميل...</span>
                                            </div>
                                            <span class="ms-2">جاري البحث عن الخدمات...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="appointment_date">التاريخ *</label>
                                        <input type="date" name="appointment_date" id="appointment_date"
                                            class="form-control" value="{{ old('appointment_date') }}"
                                            min="{{ date('Y-m-d') }}" hx-get="{{ route('admin.apis.available-times') }}"
                                            hx-trigger="change, keyup delay:300ms changed"
                                            hx-include="#doctor_id, #facility_id" hx-target="#appointment_time"
                                            hx-indicator="#time-loading" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="appointment_time">الوقت *</label>
                                        <select name="appointment_time" id="appointment_time" class="form-control"
                                            required>
                                            <option value="">اختر الوقت</option>
                                            <!-- سيتم ملء الأوقات المتاحة عبر HTMX -->
                                        </select>
                                        <div id="time-loading" class="htmx-indicator mt-2">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">جاري التحميل...</span>
                                            </div>
                                            <span class="ms-2">جاري التحقق من الأوقات المتاحة...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duration">المدة (دقيقة) *</label>
                                        <input type="number" name="duration" id="duration" class="form-control"
                                            value="{{ old('duration', 30) }}" min="15" max="240"
                                            step="15" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">الحالة *</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>جديد
                                            </option>
                                            <option value="confirmed"
                                                {{ old('status') == 'confirmed' ? 'selected' : '' }}>
                                                مؤكد</option>
                                            <option value="cancelled"
                                                {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                                ملغي</option>
                                            <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>منتهي
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="insurance_company_id">شركة التأمين</label>
                                        <select name="insurance_company_id" id="insurance_company_id"
                                            class="form-control" hx-get="{{ route('admin.apis.insurance-companies') }}"
                                            hx-trigger="focus, keyup delay:300ms changed" hx-target="#insurance-results"
                                            hx-indicator="#insurance-loading">
                                            <option value="">ابحث عن شركة التأمين...</option>
                                            @if (old('insurance_company_id'))
                                                <option value="{{ old('insurance_company_id') }}" selected>
                                                    {{ \App\Models\InsuranceCompany::find(old('insurance_company_id'))->name ?? 'تم التحديد' }}
                                                </option>
                                            @endif
                                        </select>
                                        <div id="insurance-results" class="mt-2"></div>
                                        <div id="insurance-loading" class="htmx-indicator mt-2">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">جاري التحميل...</span>
                                            </div>
                                            <span class="ms-2">جاري البحث عن شركات التأمين...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <button type="submit" class="btn btn-primary" id="submitBtn">حفظ الحجز</button>
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .search-results {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-result-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .htmx-indicator {
            display: none;
        }

        .htmx-request .htmx-indicator {
            display: block;
        }

        .htmx-request.htmx-indicator {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <!-- مكتبة HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.6"
        integrity="sha384-FhXw7b6AlE/jyjlZH5iHa/tTe9EpJ1Y55RjcgPbjeWMskSxZt1v9qkxLJWNJaGni" crossorigin="anonymous">
    </script>

    <script>
        // دالة لمعالجة النتائج وعرضها
        document.addEventListener('DOMContentLoaded', function() {
            // معالجة نتائج البحث وعرضها
            document.body.addEventListener('htmx:afterRequest', function(evt) {
                const target = evt.detail.target;
                const response = evt.detail.xhr.response;

                // إذا كان الهدف هو نتائج البحث
                if (target.id && target.id.includes('-results')) {
                    try {
                        const data = JSON.parse(response);
                        displaySearchResults(target, data);
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }

                // إذا كان الهدف هو قائمة الأوقات
                if (target.id === 'appointment_time') {
                    try {
                        const data = JSON.parse(response);
                        populateTimeSlots(target, data);
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }
            });

            // دالة لعرض نتائج البحث
            function displaySearchResults(target, data) {
                const results = data.data || data;

                if (results.length === 0) {
                    target.innerHTML = '<div class="search-result-item">لم يتم العثور على نتائج</div>';
                    return;
                }

                let html = '';
                results.forEach(item => {
                    html +=
                        `<div class="search-result-item" data-value="${item.id}" onclick="selectSearchResult('${target.id.replace('-results', '')}', '${item.id}', '${item.text.replace("'", "\\'")}')">${item.text}</div>`;
                });

                target.innerHTML = html;
                target.classList.add('search-results');
            }

            // دالة لملء قائمة الأوقات
            function populateTimeSlots(target, data) {
                const times = data.available_times || [];

                let html = '<option value="">اختر الوقت</option>';

                if (times.length === 0) {
                    html += '<option value="" disabled>لا توجد أوقات متاحة</option>';
                } else {
                    times.forEach(time => {
                        html +=
                            `<option value="${time.time}" ${time.is_available ? '' : 'disabled'}>${time.formatted_time}${time.is_available ? '' : ' (مشغول)'}</option>`;
                    });
                }

                target.innerHTML = html;
            }
        });

        // دالة لاختيار نتيجة البحث
        function selectSearchResult(fieldId, value, text) {
            const field = document.getElementById(fieldId);
            field.value = value;

            // إخفاء نتائج البحث
            const results = document.getElementById(fieldId + '-results');
            results.innerHTML = '';
            results.classList.remove('search-results');

            // تحديث النص المعروض إذا كان عنصر select
            if (field.tagName === 'SELECT') {
                const selectedOption = field.querySelector(`option[value="${value}"]`);
                if (!selectedOption) {
                    field.innerHTML = `<option value="${value}" selected>${text}</option>` + field.innerHTML;
                } else {
                    selectedOption.selected = true;
                }
            }
        }

        // التحقق من التوفر عند تحميل الصفحة إذا كانت هناك قيم مسبقة
        document.addEventListener('DOMContentLoaded', function() {
            @if (old('doctor_id') && old('appointment_date') && old('facility_id'))
                setTimeout(function() {
                    const event = new Event('change');
                    document.getElementById('appointment_date').dispatchEvent(event);
                }, 500);
            @endif
        });
    </script>
@endpush
