@extends('layouts.admin')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; direction: rtl;">
        <div class="card shadow" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4 fw-bold text-primary">تسجيل الدخول</h2>
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
@endsection
