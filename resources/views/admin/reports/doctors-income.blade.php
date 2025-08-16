<!-- This file generates reports on the income of doctors. -->

@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>تقارير دخل الأطباء</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>اسم الطبيب</th>
                <th>الدخل الإجمالي</th>
                <th>عدد المواعيد</th>
                <th>تاريخ التقرير</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctorIncomeReports as $report)
            <tr>
                <td>{{ $report->doctor_name }}</td>
                <td>{{ $report->total_income }}</td>
                <td>{{ $report->appointment_count }}</td>
                <td>{{ $report->report_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection