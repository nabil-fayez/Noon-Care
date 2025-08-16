@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>قائمة الأطباء</h1>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">إضافة طبيب جديد</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>التخصص</th>
                <th>البريد الإلكتروني</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
            <tr>
                <td>{{ $doctor->name }}</td>
                <td>{{ $doctor->specialty }}</td>
                <td>{{ $doctor->email }}</td>
                <td>
                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning">تعديل</a>
                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection