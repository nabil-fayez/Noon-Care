@extends('layouts.patient')

@section('content')
<div class="container">
    <h1>إدارة الحساب</h1>
    
    <form action="{{ route('patient.account.update') }}" method="POST">
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
            <label for="password">كلمة المرور الجديدة (اتركه فارغًا إذا لم ترغب في تغييرها)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">تأكيد كلمة المرور</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">تحديث الحساب</button>
    </form>
</div>
@endsection