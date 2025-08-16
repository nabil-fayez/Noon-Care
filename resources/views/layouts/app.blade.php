<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المواعيد الطبية</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
                <li><a href="{{ route('register') }}">إنشاء حساب</a></li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2023 نظام إدارة المواعيد الطبية. جميع الحقوق محفوظة.</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>