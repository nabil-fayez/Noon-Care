@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>تسجيل الدخول</h2>
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">تذكرني</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">تسجيل الدخول</button>

        <!-- <div class="mt-3">
            {{-- <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a> --}}
        </div> -->
    </form>
</div>
@endsection