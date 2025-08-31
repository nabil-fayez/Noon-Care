@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>حذف مريض</h1>
        <form action="{{ route('admin.facility.delete', $facility->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $facility->username }}"
                    disabled required>
            </div>
            <div class="form-group">
                <label for="business_name">الاسم التجاري</label>
                <input type="text" class="form-control" id="business_name" name="business_name"
                    value="{{ $facility->business_name }}" disabled required>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $facility->email }}"
                    disabled required>
            </div>
            <button type="submit" class="btn btn-danger">حذف المنشأة</button>
        </form>
    </div>
@endsection
