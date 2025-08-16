@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>تفاصيل المريض</h1>
    
    <div class="patient-details">
        <h2>{{ $patient->name }}</h2>
        <p><strong>البريد الإلكتروني:</strong> {{ $patient->email }}</p>
        <p><strong>رقم الهاتف:</strong> {{ $patient->phone }}</p>
        <p><strong>تاريخ الميلاد:</strong> {{ $patient->date_of_birth }}</p>
        <p><strong>العنوان:</strong> {{ $patient->address }}</p>
    </div>

    <h3>المواعيد السابقة</h3>
    <table class="table">
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>الوقت</th>
                <th>الطبيب</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->appointments as $appointment)
            <tr>
                <td>{{ $appointment->date }}</td>
                <td>{{ $appointment->time }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>{{ $appointment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.patients.index') }}" class="btn btn-primary">عودة إلى قائمة المرضى</a>
</div>
@endsection