@extends('layouts.admin')

@section('title', 'سلة محذوفات الحجوزات - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">سلة محذوفات الحجوزات</h5>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary">
                            العودة إلى الحجوزات
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>رقم الحجز</th>
                                        <th>المريض</th>
                                        <th>الطبيب</th>
                                        <th>التاريخ والوقت</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الحذف</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $appointment)
                                        <tr>
                                            <td>#{{ $appointment->id }}</td>
                                            <td>{{ $appointment->patient->first_name }}
                                                {{ $appointment->patient->last_name }}</td>
                                            <td>{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                            </td>
                                            <td>{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status_color }}">
                                                    {{ $appointment->status_text }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->deleted_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form
                                                        action="{{ route('admin.appointments.restore', $appointment->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success">استعادة</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.appointments.forceDelete', $appointment->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('هل أنت متأكد من الحذف النهائي؟')">حذف
                                                            نهائي</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">سلة المحذوفات فارغة</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- التصفح -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
