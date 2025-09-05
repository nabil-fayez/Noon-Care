@extends('layouts.admin')

@section('title', 'إضافة خدمة للمنشأة - ' . $facility->business_name . ' - Noon Care')

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
                        <a href="{{ route('admin.facilities.services', $facility) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة الخدمات
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> إضافة خدمة للمنشأة: {{ $facility->business_name }}
                    </h4>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">معلومات الخدمة</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.facility.storeService', $facility) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="service_id" class="form-label">الخدمة *</label>
                                        <select class="form-select @error('service_id') is-invalid @enderror"
                                            id="service_id" name="service_id" required>
                                            <option value="">اختر الخدمة</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">السعر (ريال) *</label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                            id="price" name="price" value="{{ old('price') }}" step="0.01"
                                            min="0" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">المدة (دقيقة) *</label>
                                        <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                            id="duration" name="duration" value="{{ old('duration') }}" min="5"
                                            required>
                                        <div class="form-text">أقل مدة allowed هي 5 دقائق</div>
                                        @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch mt-4 pt-2">
                                            <input class="form-check-input" type="checkbox" id="is_available"
                                                name="is_available" value="1" checked>
                                            <label class="form-check-label" for="is_available">
                                                الخدمة متاحة
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> إضافة
                                </button>
                                <a href="{{ route('admin.facilities.services', $facility) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
