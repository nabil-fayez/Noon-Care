@extends('layouts.admin')

@section('title', 'خدمات المنشأة - ' . $facility->business_name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.facility.show', $facility) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى بيانات المنشأة
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-list-check"></i> خدمات المنشأة: {{ $facility->business_name }}
                    </h4>
                    @can('facilities.update')
                        <a href="{{ route('admin.facility.addService', $facility) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة خدمة
                        </a>
                    @endcan
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة الخدمات</h5>
                    </div>
                    <div class="card-body">
                        @if ($services->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الخدمة</th>
                                            <th>السعر (ريال)</th>
                                            <th>المدة (دقيقة)</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $service)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ number_format($service->pivot->price, 2) }}</td>
                                                <td>{{ $service->pivot->duration }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $service->pivot->is_available ? 'success' : 'danger' }}">
                                                        {{ $service->pivot->is_available ? 'متاحة' : 'غير متاحة' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @can('facilities.update')
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editServiceModal{{ $service->id }}">
                                                                <i class="bi bi-pencil"></i> تعديل
                                                            </button>
                                                        @endcan
                                                        @can('facilities.update')
                                                            <form
                                                                action="{{ route('admin.facility.removeService', [$facility, $service]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('هل أنت متأكد من إزالة هذه الخدمة؟')">
                                                                    <i class="bi bi-trash"></i> إزالة
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>

                                                    <!-- Modal لتعديل الخدمة -->
                                                    <div class="modal fade" id="editServiceModal{{ $service->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">تعديل الخدمة:
                                                                        {{ $service->name }}</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('admin.facility.updateService', [$facility, $service]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="price{{ $service->id }}"
                                                                                class="form-label">السعر (ريال) *</label>
                                                                            <input type="number" class="form-control"
                                                                                id="price{{ $service->id }}"
                                                                                name="price"
                                                                                value="{{ $service->pivot->price }}"
                                                                                step="0.01" min="0" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="duration{{ $service->id }}"
                                                                                class="form-label">المدة (دقيقة) *</label>
                                                                            <input type="number" class="form-control"
                                                                                id="duration{{ $service->id }}"
                                                                                name="duration"
                                                                                value="{{ $service->pivot->duration }}"
                                                                                min="5" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox"
                                                                                    id="is_available{{ $service->id }}"
                                                                                    name="is_available" value="1"
                                                                                    {{ $service->pivot->is_available ? 'checked' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="is_available{{ $service->id }}">
                                                                                    الخدمة متاحة
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">إلغاء</button>
                                                                        <button type="submit" class="btn btn-primary">حفظ
                                                                            التغييرات</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">لا توجد خدمات مضافة لهذه المنشأة</p>
                                @can('facilities.update')
                                    <a href="{{ route('admin.facility.addService', $facility) }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> إضافة خدمة جديدة
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
