@extends('layouts.patient')

@section('content')
<div class="container">
    <h1>نتائج البحث عن الأطباء</h1>

    @if($doctors->isEmpty())
        <p>لا توجد نتائج مطابقة لبحثك.</p>
    @else
        <ul class="list-group">
            @foreach($doctors as $doctor)
                <li class="list-group-item">
                    <a href="{{ route('patient.doctor.details', $doctor->id) }}">
                        {{ $doctor->name }} - {{ $doctor->specialization }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection