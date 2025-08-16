@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>لوحة التحكم - الإدارة</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>إحصائيات الأطباء</h5>
                </div>
                <div class="card-body">
                    <p>عدد الأطباء: {{ $doctorsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>إحصائيات المرضى</h5>
                </div>
                <div class="card-body">
                    <p>عدد المرضى: {{ $patientsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>إحصائيات المواعيد</h5>
                </div>
                <div class="card-body">
                    <p>عدد المواعيد: {{ $appointmentsCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection