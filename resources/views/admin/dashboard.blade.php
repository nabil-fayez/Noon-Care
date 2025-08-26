@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 text-center">لوحة التحكم - الإدارة</h1>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">إحصائيات الأطباء</h5>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">عدد الأطباء: <span class="fw-bold text-primary">{{ '5' }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">إحصائيات المرضى</h5>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">عدد المرضى: <span class="fw-bold text-success">{{ '5' }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
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
