@extends('layouts.patient')

@section('content')
<div class="container">
    <h1>بحث عن الأطباء</h1>
    <form action="{{ route('patient.search.results') }}" method="GET">
        <div class="form-group">
            <label for="search">ابحث عن طبيب:</label>
            <input type="text" id="search" name="query" class="form-control" placeholder="أدخل اسم الطبيب أو التخصص">
        </div>
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>
</div>
@endsection