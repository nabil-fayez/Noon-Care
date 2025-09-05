<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    <div class="container py-4">
        <header class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">لوحة التحكم</h2>
                <div>{{ now()->format('l, j F Y') }}</div>
            </div>
        </header>

        <main>
            {{-- في أعلى الملف --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                    <br>
                    <small>إذا كنت تعتقد أن هذا خطأ، يرجى الاتصال بمسؤول النظام.</small>
                    <button id='testbtn' type="button" class="close btn btn-danger" data-dismiss="alert"
                        aria-label="Close" onclick="this.parentElement.style.display='none'">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="mt-5 text-center text-muted">
            <p>&copy; {{ date('Y') }} Noon-Care. All rights reserved.</p>
        </footer>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    <script>
        document.querySelectorAll('[title]').forEach(el => {
            el.textContent = el.title;
        });
    </script>
</body>

</html>
