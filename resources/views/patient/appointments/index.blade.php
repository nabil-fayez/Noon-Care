@extends('layouts.patient')

@section('content')
<div class="container">
    <h1>My Appointments</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->date }}</td>
                <td>{{ $appointment->time }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>{{ $appointment->status }}</td>
                <td>
                    <a href="{{ route('patient.appointments.show', $appointment->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('patient.appointments.booking', $appointment->id) }}" class="btn btn-warning">Reschedule</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection