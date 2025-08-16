@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1>تعديل الملف الشخصي</h1>
    <form action="{{ route('doctor.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">الاسم</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
        </div>

        <div class="form-group">
            <label for="phone">رقم الهاتف</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}" required>
        </div>

        <div class="form-group">
            <label for="specialization">التخصص</label>
            <input type="text" class="form-control" id="specialization" name="specialization" value="{{ auth()->user()->specialization }}" required>
        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection