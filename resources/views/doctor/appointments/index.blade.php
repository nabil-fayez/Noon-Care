@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1>My Appointments</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->date }}</td>
                <td>{{ $appointment->time }}</td>
                <td>{{ $appointment->patient->name }}</td>
                <td>{{ $appointment->status }}</td>
                <td>
                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="btn btn-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection