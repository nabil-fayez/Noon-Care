@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>تفاصيل الموعد</h1>
    
    <div class="appointment-details">
        <h2>معلومات الموعد</h2>
        <p><strong>رقم الموعد:</strong> {{ $appointment->id }}</p>
        <p><strong>الطبيب:</strong> {{ $appointment->doctor->name }}</p>
        <p><strong>المريض:</strong> {{ $appointment->patient->name }}</p>
        <p><strong>التاريخ:</strong> {{ $appointment->date }}</p>
        <p><strong>الوقت:</strong> {{ $appointment->time }}</p>
        <p><strong>الحالة:</strong> {{ $appointment->status }}</p>
    </div>

    <div class="actions">
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary">عودة إلى قائمة المواعيد</a>
    </div>
</div>
@endsection