@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>تعديل بيانات المريض</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.patient.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $patient->username }}"
                    required>
            </div>

            <div class="form-group">
                <label for="first_name">الاسم الأول</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="{{ $patient->first_name }}" required>
            </div>

            <div class="form-group">
                <label for="last_name">الاسم الأخير</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $patient->last_name }}"
                    required>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $patient->email }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">تحديث المريض</button>
        </form>
    </div>
@endsection
