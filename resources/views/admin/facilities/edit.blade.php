@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Facility</h1>

    <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Facility Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $facility->name }}" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $facility->address }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $facility->phone }}" required>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="hospital" {{ $facility->type == 'hospital' ? 'selected' : '' }}>Hospital</option>
                <option value="clinic" {{ $facility->type == 'clinic' ? 'selected' : '' }}>Clinic</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Facility</button>
    </form>
</div>
@endsection