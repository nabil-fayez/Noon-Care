@extends('layouts.admin')

@section('title', 'تفاصيل الحجز - Noon Care')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('admin.partials.sidebar')

        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تفاصيل الحجز #{{ $appointment->id }}</h5>
                    <div>
                        <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-primary btn-sm">تعديل</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">المريض:</th>
                                    <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>الطبيب:</th>
                                    <td>{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>المنشأة:</th>
                                    <td>{{ $appointment->facility->business_name }}</td>
                                </tr>
                                <tr>
                                    <th>الخدمة:</th>
                                    <td>{{ $appointment->service->name ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">التاريخ والوقت:</th>
                                    <td>{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>المدة:</th>
                                    <td>{{ $appointment->duration }} دقيقة</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status_color }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>السعر:</th>
                                    <td>{{ $appointment->price ? number_format($appointment->price, 2) . ' ر.س' : 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($appointment->insurance_company_id)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>معلومات التأمين:</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">شركة التأمين:</th>
                                    <td>{{ $appointment->insuranceCompany->name }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>ملاحظات:</h6>
                            <div class="border p-3">
                                {{ $appointment->notes ?? 'لا توجد ملاحظات' }}
                            </div>
                        </div>
                    </div>

                    @if($appointment->cancellation_reason)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>سبب الإلغاء:</h6>
                            <div class="border p-3 bg-light">
                                {{ $appointment->cancellation_reason }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route('admin.appointments.updateStatus', $appointment) }}" method="POST" class="d-inline">
                            @csrf
                            <div class="btn-group" role="group">
                                <button type="submit" name="status" value="confirmed" class="btn btn-success">تأكيد الحجز</button>
                                <button type="submit" name="status" value="completed" class="btn btn-info">تم الإكمال</button>
                                <button type="submit" name="status" value="cancelled" class="btn btn-danger">إلغاء الحجز</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
