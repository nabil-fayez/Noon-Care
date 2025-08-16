@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Doctor</h1>

    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $doctor->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $doctor->email }}" required>
        </div>

        <div class="form-group">
            <label for="specialization">Specialization</label>
            <input type="text" class="form-control" id="specialization" name="specialization" value="{{ $doctor->specialization }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $doctor->phone }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Doctor</button>
    </form>
</div>
@endsection