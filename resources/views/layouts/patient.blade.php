<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة تحكم المريض - Noon Care')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            padding-top: 56px;
            width: 250px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #0d6efd;
        }

        .sidebar .nav-link i {
            margin-left: 0.5rem;
        }

        main {
            margin-right: 250px;
            padding: 20px;
            padding-top: 76px;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }

        footer {
            margin-right: 250px;
            text-align: center;
            padding: 20px;
            background-color: white;
            border-top: 1px solid #e3e6f0;
        }
    </style>
</head>

<body>
    <!-- شريط التنقل العلوي -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('patient.dashboard') }}">
                <i class="bi bi-heart-pulse"></i> Noon Care
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> لوحة التحكم
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->guard('patient')->user()->full_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('patient.profile') }}">الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="{{ route('patient.profile.edit') }}">تعديل البيانات</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('patient.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
                                </a>
                                <form id="logout-form" action="{{ route('patient.logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- الشريط الجانبي -->
    <div class="sidebar d-none d-lg-block">
        <div class="list-group list-group-flush">
            <a href="{{ route('patient.dashboard') }}"
                class="list-group-item list-group-item-action bg-transparent text-white {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> لوحة التحكم
            </a>
            <a href="{{ route('patient.doctors.index') }}"
                class="list-group-item list-group-item-action bg-transparent text-white {{ request()->routeIs('patient.doctors.*') ? 'active' : '' }}">
                <i class="bi bi-search-heart"></i> البحث عن أطباء
            </a>
            <a href="{{ route('patient.appointments.index') }}"
                class="list-group-item list-group-item-action bg-transparent text-white {{ request()->routeIs('patient.appointments.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> مواعيدي
            </a>
            <a href="{{ route('patient.medicalHistory') }}"
                class="list-group-item list-group-item-action bg-transparent text-white {{ request()->routeIs('patient.medicalHistory') ? 'active' : '' }}">
                <i class="bi bi-file-medical"></i> السجل الطبي
            </a>
            <a href="{{ route('patient.invoices.index') }}"
                class="list-group-item list-group-item-action bg-transparent text-white {{ request()->routeIs('patient.invoices.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> الفواتير
            </a>
            <a href="{{ route('patient.profile') }}"
                class="list-group-item list-group-item-action bg-transparent text-white {{ request()->routeIs('patient.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i> الملف الشخصي
            </a>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <main>
        <!-- رسائل التنبيه -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- التذييل -->
    <footer class="bg-light text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Noon Care. جميع الحقوق محفوظة.</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
