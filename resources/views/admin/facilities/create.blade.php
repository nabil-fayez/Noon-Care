@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>إضافة منشأة جديدة</h1>
    <form action="{{ route('admin.facilities.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">اسم المنشأة</label>
            <input type="text" class="form-control" id="name" name="name" required>
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