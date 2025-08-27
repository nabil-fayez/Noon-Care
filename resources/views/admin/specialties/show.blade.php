@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>تفاصيل التخصص</h1>

        <div class="patient-details">
            <h2>{{ $specialty->name }}</h2>
        </div>
        <a href="{{ route('admin.specialties.index') }}" class="btn btn-primary">عودة إلى قائمة المرضى</a>
    </div>
@endsection
