<!-- resources/views/specialties/index.blade.php -->
@extends('layouts.app')

@section('title', 'التخصصات الطبية - Noon Care')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center mb-5">
            <div class="col-md-10 text-center">
                <h1 class="display-5 fw-bold mb-3">التخصصات الطبية</h1>
                <p class="lead text-muted">اكتشف مجموعة واسعة من التخصصات الطبية المتاحة لدينا واختر ما يناسب احتياجاتك</p>

                <!-- شريط البحث -->
                <form method="GET" action="{{ route('specialties.index') }}" class="mt-4">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control" placeholder="ابحث عن تخصص طبي..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> بحث
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @forelse($specialties as $specialty)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 specialty-card">
                        <div class="card-body text-center p-4">
                            @if ($specialty->icon_url)
                                <img src="{{ $specialty->icon_url }}" class="rounded mb-4" width="80" height="80"
                                    alt="{{ $specialty->name }}">
                            @else
                                <div class="bg-{{ $specialty->color }} rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-heart-pulse text-white fs-3"></i>
                                </div>
                            @endif

                            <h4 class="card-title fw-bold">{{ $specialty->name }}</h4>

                            @if ($specialty->description)
                                <p class="card-text text-muted">{{ Str::limit($specialty->description, 100) }}</p>
                            @endif

                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="badge bg-primary me-2">
                                    <i class="bi bi-people"></i> {{ $specialty->doctors_count }} أطباء
                                </span>
                                <span class="badge bg-{{ $specialty->is_active ? 'success' : 'secondary' }}">
                                    {{ $specialty->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>

                            <a href="{{ route('doctors.index', ['specialty_id' => $specialty->id]) }}"
                                class="btn btn-outline-primary mt-2">
                                عرض الأطباء <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h3 class="mt-3">لا توجد تخصصات</h3>
                    <p class="text-muted">لم نتمكن من العثور على أي تخصصات تطابق بحثك</p>
                    <a href="{{ route('specialties.index') }}" class="btn btn-primary">
                        عرض جميع التخصصات
                    </a>
                </div>
            @endforelse
        </div>

        <!-- التصفح -->
        @if ($specialties->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $specialties->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .specialty-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .specialty-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        .display-5 {
            font-size: 2.5rem;
        }

        @media (max-width: 768px) {
            .display-5 {
                font-size: 2rem;
            }
        }
    </style>
@endpush
