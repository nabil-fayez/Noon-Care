@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>قائمة المرضى</h1>
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">إضافة مريض جديد</a>
    
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>رقم الهاتف</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->email }}</td>
                <td>{{ $patient->phone }}</td>
                <td>
                    <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-info">عرض</a>
                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-warning">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection