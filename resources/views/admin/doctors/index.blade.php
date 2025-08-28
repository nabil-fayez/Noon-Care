@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>قائمة الأطباء</h1>
        <a href="{{ route('admin.doctor.create') }}" class="btn btn-primary">إضافة طبيب جديد</a>

        <table class="table">
            <thead>
                <tr>
                    <th>الاسم بالكامل</th>
                    <th>اسم المستخدم</th>
                    <th>التخصص</th>
                    <th>البريد الإلكتروني</th>
                    <th>تاريخ التسجيل</th>
                    <th>تاريخ الحذف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->first_name . ' ' . $doctor->last_name }}</td>
                        <td>{{ $doctor->username }}</td>
                        <td>{{ $doctor->specialties->pluck('name')->join(', ') }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->created_at }}</td>
                        <td>{{ $doctor->deleted_at }}</td>

                        <td>
                            <a href="{{ route('admin.doctor.show', $doctor->id) }}" class="btn btn-primary">عرض</a>
                            <a href="{{ route('admin.doctor.update', $doctor->id) }}" class="btn btn-success">تعديل</a>
                            @if (!is_null($doctor->deleted_at))
                                <a href="{{ route('admin.doctor.restore', $doctor->id) }}" class="btn btn-info">استعادة</a>
                                <a href="{{ route('admin.doctor.destroy', $doctor->id) }}" class="btn btn-danger">تدمير
                                    نهائي</a>
                            @else
                                <a href="{{ route('admin.doctor.delete', $doctor->id) }}" class="btn btn-danger">حذف</a>
                            @endif
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
