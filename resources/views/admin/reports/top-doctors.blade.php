@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Top Doctors Report</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Doctor Name</th>
                <th>Specialization</th>
                <th>Appointments</th>
                <th>Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topDoctors as $doctor)
            <tr>
                <td>{{ $doctor->name }}</td>
                <td>{{ $doctor->specialization }}</td>
                <td>{{ $doctor->appointments_count }}</td>
                <td>${{ number_format($doctor->income, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection