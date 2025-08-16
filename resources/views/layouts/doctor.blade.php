<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome to the Doctor Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('doctor.profile') }}">Profile</a></li>
                    <li><a href="{{ route('doctor.prices') }}">Manage Prices</a></li>
                    <li><a href="{{ route('doctor.appointments.index') }}">Appointments</a></li>
                </ul>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Your Application Name. All rights reserved.</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>