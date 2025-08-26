@extends('layouts.admin')

@section('content')
    <div class="container">
        <H2>Logout</H2>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">
                Logout
            </button>
        </form>
    </div>
@endsection
