@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>إضافة منشأة جديدة</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.facility.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">المعرف للمنشأة</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">البريد الالكتروني</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>
            <div class="form-group">
                <label for="business_name">الاسم التجاري</label>
                <input type="text" class="form-control" id="business_name" name="business_name" required>
            </div>
            <div class="form-group">
                <label for="address">العنوان</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="type">نوع المنشأة</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="hospital">مستشفى</option>
                    <option value="clinic">عيادة</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">إضافة المنشأة</button>
        </form>
    </div>
@endsection
