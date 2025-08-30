@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>حذف مريض</h1>
        <form action="{{ route('admin.patient.delete', $patient->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $patient->username }}"
                    disabled required>
            </div>
            <div class="form-group">
                <label for="first_name">الاسم الأول</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="{{ $patient->first_name }}" disabled required>
            </div>
            <div class="form-group
            ">
                <label for="last_name">اسم العائلة</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $patient->last_name }}"
                    disabled required>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $patient->email }}"
                    disabled required>
            </div>
            <button type="submit" class="btn btn-danger">حذف المريض</button>
        </form>
    </div>
@endsection
