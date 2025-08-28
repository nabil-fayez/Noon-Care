@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>حذف تخصص</h1>
        <form action="{{ route('admin.specialty.delete', $specialty->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $specialty->name }}" disabled
                    required>
            </div>
            <button type="submit" class="btn btn-danger">حذف التخصص</button>
        </form>
    </div>
@endsection
