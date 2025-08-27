@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>قائمة التخصصات</h1>
        <a href="{{ route('admin.specialty.create') }}" class="btn btn-primary">إضافة تخصص جديد</a>

        <table class="table">
            <thead>
                <tr>
                    <th>رقم التخصص</th>
                    <th>اسم التخصص</th>
                    <th>تاريخ الاضافة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($specialties as $specialty)
                    <tr>
                        <td>{{ $specialty->id }}</td>
                        <td>{{ $specialty->name }}</td>
                        <td>{{ $specialty->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.specialty.show', $specialty->id) }}" class="btn btn-info">عرض</a>
                            <a href="{{ route('admin.specialty.update', $specialty->id) }}" class="btn btn-success">تعديل</a>
                            <a href="{{ route('admin.specialty.delete', $specialty->id) }}" class="btn btn-danger">حذف</a>
                        </td>
                    </tr>
                @endforeach
                @for ($i = 1; $i <= $pages; $i++)
                    <a href="{{ route('admin.specialties.index', ['page' => $i]) }}"
                        class="btn {{ $currentPage == $i ? 'btn-primary' : 'btn-secondary' }}">{{ $i }}</a>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
