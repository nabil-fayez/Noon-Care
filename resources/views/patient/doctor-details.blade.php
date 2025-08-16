@extends('layouts.patient')

@section('content')
<div class="doctor-details">
    <h1>{{ $doctor->name }}</h1>
    <p><strong>Specialization:</strong> {{ $doctor->specialization }}</p>
    <p><strong>Experience:</strong> {{ $doctor->experience }} years</p>
    <p><strong>Contact:</strong> {{ $doctor->contact }}</p>
    <p><strong>Location:</strong> {{ $doctor->location }}</p>
    <p><strong>About:</strong> {{ $doctor->about }}</p>

    <h2>Available Appointments</h2>
    <ul>
        @foreach($doctor->appointments as $appointment)
            <li>{{ $appointment->date }} at {{ $appointment->time }}</li>
        @endforeach
    </ul>

    <a href="{{ route('patient.appointments.booking', ['doctor_id' => $doctor->id]) }}" class="btn btn-primary">Book an Appointment</a>
</div>
@endsection