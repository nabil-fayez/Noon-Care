<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.doctors.index') }}">Doctors</a></li>
                    <li><a href="{{ route('admin.patients.index') }}">Patients</a></li>
                    <li><a href="{{ route('admin.facilities.index') }}">Facilities</a></li>
                    <li><a href="{{ route('admin.appointments.index') }}">Appointments</a></li>
                    <li><a href="{{ route('admin.reports.bookings') }}">Reports</a></li>
                </ul>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>