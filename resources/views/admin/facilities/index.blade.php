@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>قائمة المنشآت الصحية</h1>
        <a href="{{ route('admin.facility.create') }}" class="btn btn-primary">إضافة منشأة جديدة</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المنشأة</th>
                    <th>العنوان</th>
                    <th>الهاتف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($facilities as $facility)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $facility->business_name }}</td>
                        <td>{{ $facility->address }}</td>
                        <td>{{ $facility->phone }}</td>
                        <td>
                            <a href="{{ route('admin.facility.show', $facility->id) }}" class="btn btn-primary">عرض</a>
                            <a href="{{ route('admin.facility.update', $facility->id) }}" class="btn btn-success">تعديل</a>
                            @if (!is_null($facility->deleted_at))
                                <a href="{{ route('admin.facility.restore', $facility->id) }}"
                                    class="btn btn-info">استعادة</a>
                                <a href="{{ route('admin.facility.destroy', $facility->id) }}" class="btn btn-danger">تدمير
                                    نهائي</a>
                            @else
                                <a href="{{ route('admin.facility.delete', $facility->id) }}" class="btn btn-danger">حذف</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @for ($i = 1; $i <= $pages; $i++)
                    <a href="{{ route('admin.facilities.index', ['page' => $i]) }}"
                        class="btn {{ $currentPage == $i ? 'btn-primary' : 'btn-secondary' }}">{{ $i }}</a>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
