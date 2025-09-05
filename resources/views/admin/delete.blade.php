@extends('layouts.admin')

@section('title', 'حذف مسؤول - ' . $admin->name . ' - Noon Care')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')

            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> العودة إلى قائمة المسؤولين
                        </a>
                        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-info ms-2">
                            <i class="bi bi-eye"></i> عرض التفاصيل
                        </a>
                    </div>
                    <h4 class="mb-0">
                        <i class="bi bi-trash"></i> حذف المسؤول: {{ $admin->name }}
                    </h4>
                </div>

                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">تأكيد الحذف</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h5><i class="bi bi-exclamation-triangle"></i> تحذير!</h5>
                            <p>أنت على وشك حذف المسؤول <strong>{{ $admin->name }}</strong>. هذه العملية لا يمكن التراجع
                                عنها.</p>
                            <p>سيتم حذف جميع البيانات المرتبطة بهذا المسؤول بما في ذلك سجلات النشاط.</p>
                        </div>

                        <div class="mb-3">
                            <strong>الاسم:</strong> {{ $admin->name }}<br>
                            <strong>البريد الإلكتروني:</strong> {{ $admin->email }}<br>
                            <strong>الدور:</strong> {{ $admin->role->role_name ?? 'بدون دور' }}<br>
                            <strong>تاريخ الإنشاء:</strong> {{ $admin->created_at->format('Y/m/d') }}
                        </div>

                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}">
                            @csrf
                            @method('DELETE')

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="confirm_delete" name="confirm_delete"
                                    required>
                                <label class="form-check-label" for="confirm_delete">
                                    أنا أدرك عواقب هذا الحذف وأريد المتابعة
                                </label>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                                    <i class="bi bi-trash"></i> حذف نهائي
                                </button>
                                <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('confirm_delete').addEventListener('change', function() {
            document.getElementById('deleteBtn').disabled = !this.checked;
        });
    </script>
@endpush
