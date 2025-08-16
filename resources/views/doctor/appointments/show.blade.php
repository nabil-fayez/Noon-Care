@extends('layouts.doctor')

@section('content')
<div class="appointment-details">
    <h1>تفاصيل الموعد</h1>
    
    <div class="appointment-info">
        <p><strong>التاريخ:</strong> {{ $appointment->date }}</p>
        <p><strong>الوقت:</strong> {{ $appointment->time }}</p>
        <p><strong>المريض:</strong> {{ $appointment->patient->name }}</p>
        <p><strong>الوصف:</strong> {{ $appointment->description }}</p>
    </div>

    <div class="actions">
        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-primary">عودة إلى قائمة المواعيد</a>
        <a href="{{ route('doctor.appointments.edit', $appointment->id) }}" class="btn btn-secondary">تعديل الموعد</a>
    </div>
</div>
@endsection