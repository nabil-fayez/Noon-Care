<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <style>
        svg {
            width: 2rem;
        }
    </style>
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

        <footer>
            <p>&copy; {{ date('Y') }} Your Application Name. All rights reserved.</p>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
