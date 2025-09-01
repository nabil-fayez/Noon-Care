<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المريض</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <header>
            <h1>مرحبًا بك في لوحة تحكم المريض</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('patient.dashboard') }}">الصفحة الرئيسية</a></li>
                    <li><a href="{{ route('patient.search') }}">البحث عن طبيب</a></li>
                    <li><a href="{{ route('patient.appointments.index') }}">مواعيدي</a></li>
                    <li><a href="{{ route('patient.account') }}">إدارة الحساب</a></li>
                </ul>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} نظام إدارة المواعيد الطبية. جميع الحقوق محفوظة.</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
