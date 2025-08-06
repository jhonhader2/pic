<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIC - Plan de Intervenciones Colectivas')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">

    <!-- Navigation -->
    @include('components.navigation')

    <!-- Main Content -->
    <main class="flex-grow-1 content-wrapper">
        <div class="page-content">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    <!-- Notificaciones en tiempo real -->
    <script>
        window.userId = {{ Auth::id() ?? 'null' }};
    </script>
    @vite(['resources/js/notifications.js'])
</body>

</html>
