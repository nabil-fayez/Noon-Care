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
                @foreach ($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->name }}</td>
                        <td>{{ $doctor->specialty }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning">تعديل</a>
                            <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @for ($i = 1; $i <= $pages; $i++)
                    <a href="{{ route('admin.doctors.index', ['page' => $i]) }}"
                        class="btn {{ $currentPage == $i ? 'btn-primary' : 'btn-secondary' }}">{{ $i }}</a>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
