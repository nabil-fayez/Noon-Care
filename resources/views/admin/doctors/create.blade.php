@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>إضافة طبيب جديد</h1>
    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">الاسم</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="specialization">التخصص</label>
            <input type="text" class="form-control" id="specialization" name="specialization" required>
        </div>
        <div class="form-group">
            <label for="phone">رقم الهاتف</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <button type="submit" class="btn btn-primary">إضافة طبيب</button>
    </form>
</div>
@endsection