<!-- This file provides a form for booking a new appointment. -->

@extends('layouts.patient')

@section('content')
<div class="container">
    <h2>حجز موعد جديد</h2>
    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="doctor_id">اختر الطبيب:</label>
            <select name="doctor_id" id="doctor_id" class="form-control" required>
                <option value="">اختر طبيباً</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="appointment_date">تاريخ الموعد:</label>
            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="appointment_time">وقت الموعد:</label>
            <input type="time" name="appointment_time" id="appointment_time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">حجز الموعد</button>
    </form>
</div>
@endsection