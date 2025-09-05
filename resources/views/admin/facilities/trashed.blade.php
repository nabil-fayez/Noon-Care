@extends('layouts.admin')

@section('title', 'المنشآت المحذوفة - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-trash"></i> المنشآت المحذوفة
                        </h5>
                        <div>
                            <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> العودة إلى قائمة المنشآت
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($facilities->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>الشعار</th>
                                            <th>اسم المنشأة</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>الهاتف</th>
                                            <th>تاريخ الحذف</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($facilities as $facility)
                                            <tr>
                                                <td>
                                                    <img src="{{ $facility->logo_url ?? 'https://via.placeholder.com/50' }}"
                                                        class="rounded" width="50" height="50" alt="شعار المنشأة">
                                                </td>
                                                <td>
                                                    <strong>{{ $facility->business_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">@{{ $facility - > username }}</small>
                                                </td>
                                                <td>{{ $facility->email ?? 'غير متوفر' }}</td>
                                                <td>{{ $facility->phone ?? 'غير متوفر' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $facility->deleted_at->format('Y-m-d H:i') }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $facility->deleted_at->diffForHumans() }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('admin.facility.restore', $facility->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('هل أنت متأكد من استعادة هذه المنشأة؟')">
                                                                <i class="bi bi-arrow-clockwise"></i> استعادة
                                                            </button>
                                                        </form>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                            data-facility-id="{{ $facility->id }}"
                                                            data-facility-name="{{ $facility->business_name }}">
                                                            <i class="bi bi-trash-fill"></i> حذف نهائي
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- التصفح -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $facilities->links() }}
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-trash display-4"></i>
                                <h4 class="mt-3">سلة المحذوفات فارغة</h4>
                                <p class="mb-4">لا توجد أي منشآت محذوفة حالياً</p>
                                <a href="{{ route('admin.facilities.index') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left"></i> العودة إلى قائمة المنشآت
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for permanent deletion -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle"></i> تأكيد الحذف النهائي
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من أنك تريد حذف المنشأة <strong id="facilityName"></strong> نهائياً؟</p>
                    <p class="text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        تحذير: هذه العملية لا يمكن التراجع عنها وسيتم حذف جميع بيانات المنشأة بشكل نهائي.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف نهائي</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // تهيئة modal الحذف النهائي
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const facilityId = button.getAttribute('data-facility-id');
            const facilityName = button.getAttribute('data-facility-name');

            // تحديث النص في الـ modal
            document.getElementById('facilityName').textContent = facilityName;

            // تحديث action في الفورم
            const form = document.getElementById('deleteForm');
            form.action = `/admin/facilities/${facilityId}/force-destroy`;
        });

        // إضافة تأثيرات للجدول
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';

                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 100);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
            transition: background-color 0.2s ease;
        }

        .badge {
            font-size: 0.75em;
        }

        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
@endpush
