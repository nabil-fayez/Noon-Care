@extends('layouts.patient')

@section('title', 'الملف الشخصي - Noon Care')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('patient.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>الملف الشخصي</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ auth()->guard('patient')->user()->profile_image_url }}" class="rounded-circle"
                                    width="150" height="150">
                                <h4 class="mt-3">{{ auth()->guard('patient')->user()->full_name }}</h4>
                                <p class="text-muted">{{ auth()->guard('patient')->user()->username }}</p>
                                <a href="{{ route('patient.profile.edit') }}" class="btn btn-primary">تعديل الملف</a>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>البريد الإلكتروني:</strong><br>
                                            {{ auth()->guard('patient')->user()->email }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>رقم الهاتف:</strong><br>
                                            {{ auth()->guard('patient')->user()->phone ?? 'غير متوفر' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>العمر:</strong><br>
                                            {{ auth()->guard('patient')->user()->age }} سنة
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>الجنس:</strong><br>
                                            {{ auth()->guard('patient')->user()->gender == 'male' ? 'ذكر' : 'أنثى' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>فصيلة الدم:</strong><br>
                                            {{ auth()->guard('patient')->user()->blood_type ?? 'غير محدد' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>جهة الاتصال في حالات الطوارئ:</strong><br>
                                            {{ auth()->guard('patient')->user()->emergency_contact ?? 'غير متوفر' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>العنوان:</strong><br>
                                    {{ auth()->guard('patient')->user()->address ?? 'غير متوفر' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
