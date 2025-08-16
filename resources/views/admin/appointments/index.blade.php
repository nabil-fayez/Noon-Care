@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>قائمة المواعيد</h1>
    <table class="table">
        <thead>
            <tr>
                <th>رقم الموعد</th>
                <th>اسم المريض</th>
                <th>اسم الطبيب</th>
                <th>تاريخ الموعد</th>
                <th>الوقت</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->id }}</td>
                <td>{{ $appointment->patient->name }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>{{ $appointment->date }}</td>
                <td>{{ $appointment->time }}</td>
                <td>
                    <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-info">عرض</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection