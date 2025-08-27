<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container py-4">
        <header class="mb-4">
            <h1 class="mb-3">Admin Dashboard</h1>
            <nav>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.doctors.index') }}">Doctors</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('admin.specialties.index') }}">Specialties</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.patients.index') }}">Patients</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.facilities.index') }}">Facilities</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('admin.appointments.index') }}">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.reports.bookings') }}">Reports</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.logout') }}">Logout</a>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="mt-5 text-center text-muted">
            <p>&copy; {{ date('Y') }} Noon-Care. All rights reserved.</p>
        </footer>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
