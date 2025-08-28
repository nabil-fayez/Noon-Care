@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>تفاصيل الطبيب</h1>
        <div class="patient-details">
            <h2>{{ $doctor->first_name . ' ' . $doctor->last_name }}</h2>
            <p><strong>اسم المستخدم:</strong> {{ $doctor->username }}</p>
            <p><strong>التخصصات:</strong> {{ $doctor->specialties->pluck('name')->join(', ') }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $doctor->email }}</p>
            <p><strong>تاريخ التسجيل:</strong> {{ $doctor->created_at }}</p>
            <p><strong>تاريخ الحذف:</strong> {{ $doctor->deleted_at }}</p>
        </div>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-primary">عودة إلى قائمة الاطباء</a>
    </div>
@endsection
