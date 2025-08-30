@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>قائمة المرضي</h1>
        <a href="{{ route('admin.patient.create') }}" class="btn btn-primary">إضافة مريض جديد</a>

        <table class="table">
            <thead>
                <tr>
                    <th>الاسم بالكامل</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>تاريخ التسجيل</th>
                    <th>تاريخ الحذف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patients as $patient)
                    <tr>
                        <td>{{ $patient->first_name . ' ' . $patient->last_name }}</td>
                        <td>{{ $patient->username }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->created_at }}</td>
                        <td>{{ $patient->deleted_at }}</td>

                        <td>
                            <a href="{{ route('admin.patient.show', $patient->id) }}" class="btn btn-primary">عرض</a>
                            <a href="{{ route('admin.patient.update', $patient->id) }}" class="btn btn-success">تعديل</a>
                            @if (!is_null($patient->deleted_at))
                                <a href="{{ route('admin.patient.restore', $patient->id) }}" class="btn btn-info">استعادة</a>
                                <a href="{{ route('admin.patient.destroy', $patient->id) }}" class="btn btn-danger">تدمير
                                    نهائي</a>
                            @else
                                <a href="{{ route('admin.patient.delete', $patient->id) }}" class="btn btn-danger">حذف</a>
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
