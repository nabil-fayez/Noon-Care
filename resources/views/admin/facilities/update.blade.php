@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Edit Facility</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.facility.update', $facility->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="business_name">Facility Name</label>
                <input type="text" class="form-control" id="business_name" name="business_name"
                    value="{{ $facility->business_name }}" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $facility->address }}"
                    required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $facility->phone }}"
                    required>
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
