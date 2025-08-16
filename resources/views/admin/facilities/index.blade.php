@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>قائمة المنشآت الصحية</h1>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">إضافة منشأة جديدة</a>
    
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
            @foreach($facilities as $facility)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $facility->name }}</td>
                <td>{{ $facility->address }}</td>
                <td>{{ $facility->phone }}</td>
                <td>
                    <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="btn btn-warning">تعديل</a>
                    <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" style="display:inline;">
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