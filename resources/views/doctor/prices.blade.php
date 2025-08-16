@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1>إدارة الأسعار</h1>
    <p>يمكنك هنا إدارة أسعار الخدمات التي تقدمها.</p>

    <table class="table">
        <thead>
            <tr>
                <th>اسم الخدمة</th>
                <th>السعر</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->price }} ريال</td>
                <td>
                    <a href="{{ route('doctor.prices.edit', $service->id) }}" class="btn btn-warning">تعديل</a>
                    <form action="{{ route('doctor.prices.destroy', $service->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('doctor.prices.create') }}" class="btn btn-primary">إضافة خدمة جديدة</a>
</div>
@endsection