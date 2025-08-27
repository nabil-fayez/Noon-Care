@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>إضافة تخصص جديد</h1>
        <form action="{{ route('admin.specialty.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">إضافة التخصص</button>
        </form>
    </div>
@endsection
