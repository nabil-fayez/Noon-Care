@extends('layouts.admin')

@section('title', 'تفاصيل التخصص - ' . $specialty->name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <!-- أزرار التنقل -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.specialties.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة التخصصات
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.specialty.edit', $specialty) }}" class="btn btn-primary me-2">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                        <form action="{{ route('admin.specialty.toggleStatus', $specialty) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $specialty->is_active ? 'warning' : 'success' }} me-2">
                                {{ $specialty->is_active ? 'تعطيل' : 'تفعيل' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.specialty.delete', $specialty) }}" class="btn btn-danger">
                            <i class="bi bi-trash"></i> حذف
                        </a>
                    </div>
                </div>

                <!-- بطاقة تفاصيل التخصص -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-tag"></i> تفاصيل التخصص
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    @if ($specialty->icon_url)
                                        <img src="{{ $specialty->icon_url }}" class="rounded mb-3" width="150"
                                            height="150" alt="أيقونة التخصص">
                                    @else
                                        <div class="bg-{{ $specialty->color }} rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                            style="width: 150px; height: 150px;">
                                            <i class="bi bi-heart-pulse text-white fs-1"></i>
                                        </div>
                                    @endif

                                    <h3>{{ $specialty->name }}</h3>

                                    <div class="mb-3">
                                        <span class="badge bg-{{ $specialty->is_active ? 'success' : 'secondary' }} fs-6">
                                            {{ $specialty->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </div>

                                    @if ($specialty->color)
                                        <div class="mb-3">
                                            <span class="badge"
                                                style="background-color: {{ $specialty->color }}; color: white">
                                                لون التخصص
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- التفاصيل الإضافية -->
                            <div class="col-md-8">
                                <!-- الوصف -->
                                @if ($specialty->description)
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">الوصف</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ $specialty->description }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- الإحصائيات -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">الإحصائيات</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-primary">{{ $specialty->doctors_count }}</h4>
                                                    <p class="mb-0">عدد الأطباء</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-success">{{ $specialty->created_at->diffForHumans() }}
                                                    </h4>
                                                    <p class="mb-0">تاريخ الإضافة</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-info">{{ $specialty->updated_at->diffForHumans() }}
                                                    </h4>
                                                    <p class="mb-0">آخر تحديث</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- قائمة الأطباء -->
                        <div class="card mt-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">الأطباء في هذا التخصص</h6>
                                <span class="badge bg-primary">{{ $specialty->doctors_count }} طبيب</span>
                            </div>
                            <div class="card-body">
                                @if ($specialty->doctors->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>الصورة</th>
                                                    <th>اسم الطبيب</th>
                                                    <th>البريد الإلكتروني</th>
                                                    <th>الحالة</th>
                                                    <th>تاريخ الانضمام</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($specialty->doctors as $doctor)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ $doctor->profile_image_url }}"
                                                                class="rounded-circle" width="40" height="40"
                                                                alt="صورة الطبيب">
                                                        </td>
                                                        <td>{{ $doctor->full_name }}</td>
                                                        <td>{{ $doctor->email }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $doctor->is_verified ? 'success' : 'warning' }}">
                                                                {{ $doctor->is_verified ? 'موثق' : 'غير موثق' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $doctor->created_at->format('Y-m-d') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.doctor.show', $doctor) }}"
                                                                class="btn btn-sm btn-info">
                                                                عرض
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-person-x display-4"></i>
                                        <p class="mt-3">لا يوجد أطباء في هذا التخصص</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
