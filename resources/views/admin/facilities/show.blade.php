@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>تفاصيل المنشأة الطبية</h1>

        <div class="patient-details">
            <h2>{{ $facility->business_name }}</h2>
            <p><strong>اسم المستخدم:</strong> {{ $facility->username }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $facility->email }}</p>
            <p><strong>تاريخ التسجيل:</strong> {{ $facility->created_at }}</p>
            <p><strong>تاريخ الحذف:</strong> {{ $facility->deleted_at }}</p>
        </div>

        <a href="{{ route('admin.facilities.index') }}" class="btn btn-primary">عودة إلى قائمة المرضى</a>
    </div>
@endsection
