@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1>لوحة تحكم الطبيب</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>الإحصائيات</h5>
                </div>
                <div class="card-body">
                    <p>عدد المواعيد: {{ $appointmentsCount }}</p>
                    <p>عدد المرضى: {{ $patientsCount }}</p>
                    <p>الإيرادات: {{ $revenue }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>آخر المواعيد</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($latestAppointments as $appointment)
                            <li class="list-group-item">
                                {{ $appointment->date }} - {{ $appointment->patient->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection