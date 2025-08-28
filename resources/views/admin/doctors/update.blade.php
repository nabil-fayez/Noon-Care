@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>تعديل بيانات الطبيب</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.doctor.update', $doctor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $doctor->username }}"
                    required>
            </div>

            <div class="form-group">
                <label for="first_name">الاسم الأول</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="{{ $doctor->first_name }}" required>
            </div>

            <div class="form-group">
                <label for="last_name">الاسم الأخير</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $doctor->last_name }}"
                    required>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $doctor->email }}"
                    required>
            </div>

            <div class="form-group">
                <label>التخصصات</label>
                <div class="row">
                    @foreach ($specialties as $specialty)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="specializations[]"
                                    value="{{ $specialty->id }}" id="specialty_{{ $specialty->id }}"
                                    {{ in_array($specialty->id, $selectedSpecialties) ? 'checked' : '' }}>
                                <label class="form-check-label" for="specialty_{{ $specialty->id }}">
                                    {{ $specialty->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">تحديث الطبيب</button>
        </form>
    </div>
@endsection
