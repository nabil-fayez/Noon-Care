@extends('layouts.admin')

@section('title', 'حذف الحجز - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h5>حذف الحجز #{{ $appointment->id }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h5><i class="bi bi-exclamation-triangle"></i> تنبيه مهم</h5>
                            <p>أنت على وشك حذف الحجز التالي. هذه العملية لا يمكن التراجع عنها.</p>
                        </div>

                        <div class="appointment-details">
                            <h6>تفاصيل الحجز:</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">المريض:</th>
                                    <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>الطبيب:</th>
                                    <td>{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>التاريخ والوقت:</th>
                                    <td>{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status_color }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="form-group mt-3">
                                <label for="reason">سبب الحذف (اختياري)</label>
                                <textarea name="reason" id="reason" class="form-control" rows="2" placeholder="أدخل سبب الحذف إذا لزم الأمر"></textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا الحجز؟')">تأكيد الحذف</button>
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
