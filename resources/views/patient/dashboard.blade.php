@extends('layouts.patient')

@section('content')
<div class="container">
    <h1>مرحبًا بك في لوحة التحكم الخاصة بك</h1>
    <p>هنا يمكنك إدارة مواعيدك، البحث عن الأطباء، وتحديث معلومات حسابك.</p>

    <div class="dashboard-cards">
        <div class="card">
            <h2>مواعيدي</h2>
            <p><a href="{{ route('patient.appointments.index') }}">عرض المواعيد</a></p>
        </div>
        <div class="card">
            <h2>البحث عن طبيب</h2>
            <p><a href="{{ route('patient.search') }}">ابدأ البحث</a></p>
        </div>
        <div class="card">
            <h2>إدارة الحساب</h2>
            <p><a href="{{ route('patient.account') }}">تحديث المعلومات</a></p>
        </div>
    </div>
</div>
@endsection