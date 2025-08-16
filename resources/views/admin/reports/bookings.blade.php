<!-- This file generates reports related to bookings. -->

@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>تقارير الحجوزات</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>رقم الحجز</th>
                <th>اسم المريض</th>
                <th>اسم الطبيب</th>
                <th>تاريخ الحجز</th>
                <th>حالة الحجز</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->patient->name }}</td>
                    <td>{{ $booking->doctor->name }}</td>
                    <td>{{ $booking->date }}</td>
                    <td>{{ $booking->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection