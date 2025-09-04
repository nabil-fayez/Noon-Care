@extends('layouts.admin')

@section('title', 'السجل الطبي للمريض - ' . $patient->full_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <!-- رسائل التنبيه -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.patient.show', $patient) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى تفاصيل المريض
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-file-medical"></i> السجل الطبي للمريض: {{ $patient->full_name }}
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                        <i class="bi bi-plus-circle"></i> إضافة سجل طبي
                    </button>
                </div>

                <!-- بطاقة السجل الطبي -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">السجل الطبي</h5>
                    </div>
                    <div class="card-body">
                        @if ($medicalRecords->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>نوع السجل</th>
                                            <th>العنوان</th>
                                            <th>الطبيب</th>
                                            <th>المنشأة</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($medicalRecords as $record)
                                            <tr>
                                                <td>{{ $record->record_date->format('Y-m-d') }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $record->record_type }}</span>
                                                </td>
                                                <td>{{ $record->title }}</td>
                                                <td>{{ $record->doctor->full_name ?? 'غير محدد' }}</td>
                                                <td>{{ $record->facility->business_name ?? 'غير محدد' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $record->status == 'active' ? 'success' : ($record->status == 'completed' ? 'primary' : 'secondary') }}">
                                                        {{ $record->status == 'active' ? 'نشط' : ($record->status == 'completed' ? 'مكتمل' : 'ملغى') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info view-record" data-bs-toggle="modal"
                                                        data-bs-target="#viewRecordModal"
                                                        data-record="{{ json_encode($record) }}">
                                                        <i class="bi bi-eye"></i> عرض
                                                    </button>
                                                    <button class="btn btn-sm btn-primary edit-record"
                                                        data-bs-toggle="modal" data-bs-target="#editRecordModal"
                                                        data-record="{{ json_encode($record) }}">
                                                        <i class="bi bi-pencil"></i> تعديل
                                                    </button>
                                                    <form action="{{ route('admin.medical_record.destroy', $record) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                            <i class="bi bi-trash"></i> حذف
                                                        </button>
                                                    </form>
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
                                <h4 class="mt-3">لا توجد سجلات طبية</h4>
                                <p class="mb-4">لم يتم إضافة أي سجلات طبية لهذا المريض حتى الآن</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                                    <i class="bi bi-plus-circle"></i> إضافة أول سجل طبي
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة سجل طبي -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecordModalLabel">إضافة سجل طبي جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.medical_record.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="record_type" class="form-label">نوع السجل <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="record_type" name="record_type" required>
                                        <option value="">اختر نوع السجل</option>
                                        <option value="consultation">استشارة</option>
                                        <option value="examination">فحص</option>
                                        <option value="test">تحليل</option>
                                        <option value="prescription">وصفة طبية</option>
                                        <option value="diagnosis">تشخيص</option>
                                        <option value="treatment">علاج</option>
                                        <option value="surgery">عملية جراحية</option>
                                        <option value="follow_up">متابعة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="record_date" class="form-label">تاريخ السجل <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="record_date" name="record_date"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان السجل <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3" hidden>
                            <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="status" name="status" value="active"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">الطبيب</label>
                                    <select class="form-select" id="doctor_id" name="doctor_id">
                                        <option value="">اختر الطبيب</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facility_id" class="form-label">المنشأة</label>
                                    <select class="form-select" id="facility_id" name="facility_id">
                                        <option value="">اختر المنشأة</option>
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}">{{ $facility->business_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">التشخيص</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="treatment_plan" class="form-label">خطة العلاج</label>
                            <textarea class="form-control" id="treatment_plan" name="treatment_plan" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات إضافية</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ السجل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal لعرض السجل الطبي -->
    <div class="modal fade" id="viewRecordModal" tabindex="-1" aria-labelledby="viewRecordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecordModalLabel">تفاصيل السجل الطبي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="recordDetails">
                    <!-- سيتم ملؤها بالجافاسكريبت -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal لتعديل سجل طبي -->
    <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRecordModalLabel">تعديل السجل الطبي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRecordForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="record_id" id="edit_record_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_record_type" class="form-label">نوع السجل <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_record_type" name="record_type" required>
                                        <option value="">اختر نوع السجل</option>
                                        <option value="consultation">استشارة</option>
                                        <option value="examination">فحص</option>
                                        <option value="test">تحليل</option>
                                        <option value="prescription">وصفة طبية</option>
                                        <option value="diagnosis">تشخيص</option>
                                        <option value="treatment">علاج</option>
                                        <option value="surgery">عملية جراحية</option>
                                        <option value="follow_up">متابعة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_record_date" class="form-label">تاريخ السجل <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edit_record_date" name="record_date"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_title" class="form-label">عنوان السجل <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">الوصف</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_doctor_id" class="form-label">الطبيب</label>
                                    <select class="form-select" id="edit_doctor_id" name="doctor_id">
                                        <option value="">اختر الطبيب</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" hidden>
                                <div class="mb-3">
                                    <label for="edit_patient_id" class="form-label">المريض</label>
                                    <input class="form-select" id="edit_patient_id" name="patient_id" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_facility_id" class="form-label">المنشأة</label>
                                    <select class="form-select" id="edit_facility_id" name="facility_id">
                                        <option value="">اختر المنشأة</option>
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}">{{ $facility->business_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_diagnosis" class="form-label">التشخيص</label>
                            <textarea class="form-control" id="edit_diagnosis" name="diagnosis" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_treatment_plan" class="form-label">خطة العلاج</label>
                            <textarea class="form-control" id="edit_treatment_plan" name="treatment_plan" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">ملاحظات إضافية</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">الحالة</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="active">نشط</option>
                                        <option value="completed">مكتمل</option>
                                        <option value="cancelled">ملغى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">خيارات إضافية</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_urgent"
                                            name="is_urgent" value="1">
                                        <label class="form-check-label" for="edit_is_urgent">
                                            حالة طارئة
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_requires_follow_up"
                                            name="requires_follow_up" value="1">
                                        <label class="form-check-label" for="edit_requires_follow_up">
                                            يحتاج متابعة
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // عرض تفاصيل السجل الطبي
        document.querySelectorAll('.view-record').forEach(button => {
            button.addEventListener('click', function() {
                const record = JSON.parse(this.getAttribute('data-record'));
                const details = `
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>نوع السجل:</strong>
                        <span class="badge bg-info">${record.record_type}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>التاريخ:</strong>
                        <span>${record.record_date}</span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <strong>العنوان:</strong>
                <p>${record.title}</p>
            </div>

            ${record.description ? `
                                                                                                                                                                        <div class="mb-3">
                                                                                                                                                                            <strong>الوصف:</strong>
                                                                                                                                                                            <p>${record.description}</p>
                                                                                                                                                                        </div>
                                                                                                                                                                        ` : ''}

            ${record.diagnosis ? `
                                                                                                                                                                        <div class="mb-3">
                                                                                                                                                                            <strong>التشخيص:</strong>
                                                                                                                                                                            <p>${record.diagnosis}</p>
                                                                                                                                                                        </div>
                                                                                                                                                                        ` : ''}

            ${record.treatment_plan ? `
                                                                                                                                                                        <div class="mb-3">
                                                                                                                                                                            <strong>خطة العلاج:</strong>
                                                                                                                                                                            <p>${record.treatment_plan}</p>
                                                                                                                                                                        </div>
                                                                                                                                                                        ` : ''}

            ${record.notes ? `
                                                                                                                                                                        <div class="mb-3">
                                                                                                                                                                            <strong>ملاحظات:</strong>
                                                                                                                                                                            <p>${record.notes}</p>
                                                                                                                                                                        </div>
                                                                                                                                                                        ` : ''}

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>الطبيب:</strong>
                        <p>${record.doctor ? record.doctor.full_name : 'غير محدد'}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>المنشأة:</strong>
                        <p>${record.facility ? record.facility.business_name : 'غير محدد'}</p>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <strong>الحالة:</strong>
                <span class="badge bg-${record.status == 'active' ? 'success' : (record.status == 'completed' ? 'primary' : 'secondary')}">
                    ${record.status == 'active' ? 'نشط' : (record.status == 'completed' ? 'مكتمل' : 'ملغى')}
                </span>
            </div>

            ${record.is_urgent ? `
                                                                                                                                                                        <div class="mb-3">
                                                                                                                                                                            <span class="badge bg-danger">حالة طارئة</span>
                                                                                                                                                                        </div>
                                                                                                                                                                        ` : ''}

            ${record.requires_follow_up ? `
                                                                                                                                                                        <div class="mb-3">
                                                                                                                                                                            <span class="badge bg-warning text-dark">يحتاج متابعة</span>
                                                                                                                                                                        </div>
                                                                                                                                                                        ` : ''}
        `;
                document.getElementById('recordDetails').innerHTML = details;
            });
        });

        // تعديل السجل الطبي
        document.querySelectorAll('.edit-record').forEach(button => {
            button.addEventListener('click', function() {
                const record = JSON.parse(this.getAttribute('data-record'));

                // ملء حقول النموذج
                document.getElementById('edit_record_id').value = record.id;
                document.getElementById('edit_record_type').value = record.record_type;
                document.getElementById('edit_record_date').value = record.record_date;

                document.getElementById('edit_title').value = record.title;
                document.getElementById('edit_description').value = record.description || '';
                document.getElementById('edit_doctor_id').value = record.doctor_id || '';
                document.getElementById('edit_patient_id').value = record.patient_id || '';
                console.log(record.patient_id);
                document.getElementById('edit_facility_id').value = record.facility_id || '';
                document.getElementById('edit_diagnosis').value = record.diagnosis || '';
                document.getElementById('edit_treatment_plan').value = record.treatment_plan || '';
                document.getElementById('edit_notes').value = record.notes || '';
                document.getElementById('edit_status').value = record.status;
                document.getElementById('edit_is_urgent').checked = record.is_urgent == 1;
                document.getElementById('edit_requires_follow_up').checked = record.requires_follow_up == 1;

                // تحديث action الخاص بالنموذج ليشمل id السجل
                const form = document.getElementById('editRecordForm');
                form.action = `/medical-record/${record.id}/update`;

                // فتح modal التعديل
                const editModal = new bootstrap.Modal(document.getElementById('editRecordModal'));
                editModal.show();
            });
        });
    </script>
@endpush
