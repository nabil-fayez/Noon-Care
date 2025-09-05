@extends('layouts.admin')

@section('title', 'أطباء المنشأة - ' . $facility->business_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.facility.show', $facility) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى المنشأة
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-people"></i> أطباء المنشأة: {{ $facility->business_name }}
                    </h4>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                        <i class="bi bi-plus-circle"></i> إضافة طبيب
                    </a>
                </div>

                <!-- جدول الأطباء -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة الأطباء</h5>
                    </div>
                    <div class="card-body">
                        @if ($doctors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>الصورة</th>
                                            <th>اسم الطبيب</th>
                                            <th>التخصصات</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>الحالة</th>
                                            <th>متاح للمواعيد</th>
                                            <th>تاريخ الإضافة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doctors as $doctor)
                                            <tr>
                                                <td>
                                                    <img src="{{ $doctor->profile_image_url ?? 'https://via.placeholder.com/50' }}"
                                                        class="rounded-circle" width="50" height="50"
                                                        alt="صورة الطبيب">
                                                </td>
                                                <td>
                                                    <strong>{{ $doctor->full_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">@{{ $doctor - > username }}</small>
                                                </td>
                                                <td>
                                                    @foreach ($doctor->specialties->take(3) as $specialty)
                                                        <span class="badge bg-info mb-1">{{ $specialty->name }}</span>
                                                    @endforeach
                                                    @if ($doctor->specialties->count() > 3)
                                                        <span
                                                            class="badge bg-secondary">+{{ $doctor->specialties->count() - 3 }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $doctor->email }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $doctor->pivot->status === 'active' ? 'success' : ($doctor->pivot->status === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ $doctor->pivot->status === 'active' ? 'نشط' : ($doctor->pivot->status === 'pending' ? 'قيد الانتظار' : 'غير نشط') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $doctor->pivot->available_for_appointments ? 'success' : 'secondary' }}">
                                                        {{ $doctor->pivot->available_for_appointments ? 'نعم' : 'لا' }}
                                                    </span>
                                                </td>
                                                <td>{{ $doctor->pivot->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.doctor.show', $doctor) }}"
                                                            class="btn btn-sm btn-info">عرض</a>
                                                        <form
                                                            action="{{ route('admin.facility.removeDoctor', [$facility, $doctor]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('هل أنت متأكد من إزالة الطبيب من المنشأة؟')">
                                                                إزالة
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- التصفح -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $doctors->links() }}
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-person-x display-4"></i>
                                <h4 class="mt-3">لا يوجد أطباء في هذه المنشأة</h4>
                                <p class="mb-4">يمكنك إضافة أطباء من خلال الزر أعلاه</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة طبيب -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorModalLabel">إضافة طبيب إلى المنشأة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.facility.addDoctor', $facility) }}" method="POST">
                    @csrf
                    <div class="modal-body">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة الطبيب</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // تهيئة select2 لاختيار الطبيب
        $(document).ready(function() {
            $('#doctor_id').select2({
                placeholder: "اختر الطبيب",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
