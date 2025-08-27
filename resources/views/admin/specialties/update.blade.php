@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>تعديل تخصص </h1>
        <form action="{{ route('admin.specialty.update', $specialty->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $specialty->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">تعديل التخصص</button>
        </form>
    </div>
@endsection
