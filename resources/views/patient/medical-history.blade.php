@extends('layouts.app')

@section('title', 'السجل الطبي - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('patient.partials.sidebar')
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">السجل الطبي</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#filterModal">
                                <i class="bi bi-funnel"></i> تصفية
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- فلترة السجلات -->
                        <form method="GET" action="{{ route('patient.medicalHistory') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="record_type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        <option value="consultation"
                                            {{ request('record_type') == 'consultation' ? 'selected' : '' }}>استشارة
                                        </option>
                                        <option value="diagnosis"
                                            {{ request('record_type') == 'diagnosis' ? 'selected' : '' }}>تشخيص</option>
                                        <option value="prescription"
                                            {{ request('record_type') == 'prescription' ? 'selected' : '' }}>وصفة طبية
                                        </option>
                                        <option value="test_result"
                                            {{ request('record_type') == 'test_result' ? 'selected' : '' }}>نتيجة تحليل
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}" placeholder="من تاريخ">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                </div>
                            </div>
                        </form>

                        @if ($medicalRecords->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>النوع</th>
                                            <th>العنوان</th>
                                            <th>الطبيب</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($medicalRecords as $record)
                                            <tr>
                                                <td>{{ $record->record_date->format('Y-m-d') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $record->is_urgent ? 'danger' : 'secondary' }}">
                                                        {{ $record->record_type }}
                                                    </span>
                                                </td>
                                                <td>{{ $record->title }}</td>
                                                <td>{{ $record->doctor->full_name }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $record->status == 'active' ? 'success' : ($record->status == 'completed' ? 'info' : 'warning') }}">
                                                        {{ $record->status == 'active' ? 'نشط' : ($record->status == 'completed' ? 'مكتمل' : 'ملغي') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('medical_record.show', $record) }}"
                                                        class="btn btn-sm btn-info">عرض</a>
                                                    @if ($record->has_attachment)
                                                        <a href="{{ $record->attachment_url }}"
                                                            class="btn btn-sm btn-outline-primary" download>
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- التصفح -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $medicalRecords->links() }}
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-file-medical display-4"></i>
                                <p class="mt-3">لا توجد سجلات طبية</p>
                                <p class="text-muted">سيظهر سجلك الطبي هنا بعد زيارتك الأولى للطبيب</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal للتصفية المتقدمة -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تصفية السجلات الطبية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ route('patient.medicalHistory') }}" id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">نوع السجل</label>
                            <select name="record_type" class="form-select">
                                <option value="">جميع الأنواع</option>
                                <option value="consultation"
                                    {{ request('record_type') == 'consultation' ? 'selected' : '' }}>استشارة</option>
                                <option value="diagnosis" {{ request('record_type') == 'diagnosis' ? 'selected' : '' }}>
                                    تشخيص</option>
                                <option value="prescription"
                                    {{ request('record_type') == 'prescription' ? 'selected' : '' }}>وصفة طبية</option>
                                <option value="test_result"
                                    {{ request('record_type') == 'test_result' ? 'selected' : '' }}>نتيجة تحليل</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الفترة الزمنية</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}" placeholder="من تاريخ">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}" placeholder="إلى تاريخ">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="filterForm" class="btn btn-primary">تطبيق التصفية</button>
                </div>
            </div>
        </div>
    </div>
@endsection
