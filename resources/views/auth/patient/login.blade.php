@extends('layouts.web')

@section('title', 'تسجيل الدخول - Noon Care')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ 'تسجيل الدخول' }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('patient.login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">كلمة المرور</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    تذكرني
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('patient.register') }}">إنشاء حساب جديد</a>
                            </div>

                            <div class="text-center mt-2">
                                <a href="{{ route('patient.password.request') }}">نسيت كلمة المرور؟</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
