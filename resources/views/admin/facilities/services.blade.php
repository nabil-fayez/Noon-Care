@extends('layouts.admin')

@section('title', 'خدمات المنشأة - ' . $facility->business_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.facility.show', $facility) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى المنشأة
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-list-check"></i> خدمات المنشأة: {{ $facility->business_name }}
                    </h4>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="bi bi-plus-circle"></i> إضافة خدمة
                    </a>
                </div>

                <!-- جدول الخدمات -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة الخدمات</h5>
                    </div>
                    <div class="card-body">
                        @if ($services->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>اسم الخدمة</th>
                                            <th>الفئة</th>
                                            <th>الوصف</th>
                                            <th>الحالة</th>
                                            <th>السعر الأساسي</th>
                                            <th>تاريخ الإضافة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $service)
                                            <tr>
                                                <td>
                                                    <strong>{{ $service->name }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $service->category ?? 'عام' }}</span>
                                                </td>
                                                <td>{{ Str::limit($service->description, 50) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $service->pivot->is_available ? 'success' : 'secondary' }}">
                                                        {{ $service->pivot->is_available ? 'متاحة' : 'غير متاحة' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $basePrice = $service->pricing
                                                            ->where('facility_id', $facility->id)
                                                            ->whereNull('insurance_company_id')
                                                            ->first();
                                                    @endphp
                                                    {{ $basePrice ? number_format($basePrice->price, 2) . ' ج.م' : 'غير محدد' }}
                                                </td>
                                                <td>{{ $service->pivot->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="#" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="#" class="btn btn-sm btn-primary">تعديل</a>
                                                        <form
                                                            action="{{ route('admin.facility.removeService', [$facility, $service]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('هل أنت متأكد من إزالة الخدمة من المنشأة؟')">
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
                                {{ $services->links() }}
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-list-check display-4"></i>
                                <h4 class="mt-3">لا توجد خدمات في هذه المنشأة</h4>
                                <p class="mb-4">يمكنك إضافة خدمات من خلال الزر أعلاه</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة خدمة -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">إضافة خدمة إلى المنشأة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.facility.addService', $facility) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">اختر الخدمة</label>
                                    <select class="form-select" id="service_id" name="service_id" required>
                                        <option value="">اختر الخدمة</option>
                                        @foreach ($availableServices as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }} -
                                                {{ $service->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">السعر الأساسي (ج.م)</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available"
                                value="1" checked>
                            <label class="form-check-label" for="is_available">
                                الخدمة متاحة
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة الخدمة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // تحديث السعر عند اختيار الخدمة
        document.getElementById('service_id').addEventListener('change', function() {
            const serviceId = this.value;
            if (serviceId) {
                // يمكنك جلب السعر الافتراضي للخدمة عبر AJAX
                fetch(`/api/services/${serviceId}/price`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.price) {
                            document.getElementById('price').value = data.price;
                        }
                    });
            }
        });
    </script>
@endpush
