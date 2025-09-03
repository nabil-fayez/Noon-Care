<div class="card mb-3">
    <div class="card-body text-center">
        <img src="{{ auth()->guard('patient')->user()->profile_image_url ?? 'https://via.placeholder.com/100' }}"
            class="rounded-circle mb-3" width="100" height="100">
        <h5>{{ auth()->guard('patient')->user()->full_name }}</h5>
        <p class="text-muted">مريض</p>
        <div class="d-flex justify-content-center">
            <span class="badge bg-success me-2">
                <i class="bi bi-heart"></i>
                {{ auth()->guard('patient')->user()->age }} سنة
            </span>
            <span class="badge bg-info">
                <i
                    class="bi bi-gender-{{ auth()->guard('patient')->user()->gender == 'male' ? 'male' : 'female' }}"></i>
                {{ auth()->guard('patient')->user()->gender == 'male' ? 'ذكر' : 'أنثى' }}
            </span>
        </div>
    </div>
</div>

<div class="list-group">
    <a href="{{ route('patient.dashboard') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> لوحة التحكم
    </a>
    <a href="{{ route('patient.appointments') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('patient.appointments') ? 'active' : '' }}">
        <i class="bi bi-calendar-check me-2"></i> المواعيد
    </a>
    <a href="{{ route('patient.medicalHistory') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('patient.medical-history') ? 'active' : '' }}">
        <i class="bi bi-file-medical me-2"></i> السجل الطبي
    </a>
    <a href="{{ route('patient.profile') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('patient.profile') ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i> الملف الشخصي
    </a>
    {{-- <a href="{{ route('patient.prescriptions') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('patient.prescriptions') ? 'active' : '' }}">
        <i class="bi bi-prescription me-2"></i> الوصفات الطبية
    </a> --}}
    <a href="{{ route('patient.invoices') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('patient.invoices') ? 'active' : '' }}">
        <i class="bi bi-receipt me-2"></i> الفواتير
    </a>
    <a href="{{ route('patient.logout') }}" class="list-group-item list-group-item-action text-danger"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
    </a>
    <form id="logout-form" action="{{ route('patient.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
