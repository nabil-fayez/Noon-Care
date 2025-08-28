@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">إحصائيات الأطباء</h5>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">عدد الأطباء: <span class="fw-bold text-primary">{{ $doctorsCount }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">إحصائيات التخصصات</h5>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">عدد التخصصات: <span class="fw-bold text-danger">{{ $specialtiesCount }}</span></p>
                        <div class="mt-3">
                            <h6>أكثر التخصصات شيوعًا:</h6>
                            <ul class="list-unstyled">
                                @foreach ($topSpecialties as $specialty)
                                    <li>{{ $specialty->name }} ({{ $specialty->doctors_count }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">إحصائيات المرضى</h5>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">عدد المرضى: <span class="fw-bold text-success">{{ '5' }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">إحصائيات المواعيد</h5>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">عدد المواعيد: <span class="fw-bold text-info">{{ '5' }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
