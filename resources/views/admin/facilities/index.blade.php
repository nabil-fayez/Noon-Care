@extends('layouts.app')

@section('title', 'المنشآت الطبية - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">المنشآت الطبية</h5>
                    </div>
                    <div class="card-body">
                        <!-- فلترة البحث -->
                        <form method="GET" action="{{ route('admin.facilities.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="بحث باسم المنشأة"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="specialty_id" class="form-select">
                                        <option value="">جميع التخصصات</option>
                                        @foreach ($specialties as $specialty)
                                            <option value="{{ $specialty->id }}"
                                                {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="service_id" class="form-select">
                                        <option value="">جميع الخدمات</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                </div>
                            </div>
                        </form>

                        <!-- قائمة المنشآت -->
                        <div class="row">
                            @forelse($facilities as $facility)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <img src="{{ $facility->logo_url ?? 'https://via.placeholder.com/80' }}"
                                                    class="rounded me-3" width="80" height="80" alt="شعار المنشأة">
                                                <div class="flex-grow-1">
                                                    <h5 class="card-title">{{ $facility->business_name }}</h5>
                                                    <p class="text-muted mb-2">
                                                        <i class="bi bi-geo-alt"></i> {{ $facility->address }}
                                                    </p>
                                                    <div class="mb-2">
                                                        @foreach ($facility->services->take(3) as $service)
                                                            <span class="badge bg-info">{{ $service->name }}</span>
                                                        @endforeach
                                                        @if ($facility->services->count() > 3)
                                                            <span
                                                                class="badge bg-secondary">+{{ $facility->services->count() - 3 }}
                                                                أكثر</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted me-3">
                                                            <i class="bi bi-people"></i>
                                                            {{ $facility->doctors_count }} طبيب
                                                        </small>
                                                        <div class="rating">
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                            <span class="ms-1">4.5</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if ($facility->phone)
                                                        <a href="tel:{{ $facility->phone }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-telephone"></i> اتصل
                                                        </a>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.facility.show', $facility) }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-eye"></i> عرض التفاصيل
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-building display-4"></i>
                                        <p class="mt-3">لا توجد منشآت طبية</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- التصفح -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $facilities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
