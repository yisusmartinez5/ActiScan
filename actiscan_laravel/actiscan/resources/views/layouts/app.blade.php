<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ActiScan')</title>
    <link rel="icon" href="{{ asset('img/favicon-actiscan.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="@yield('body_class')" data-page="@yield('page')">
    @yield('app_content')
    <script>
        window.ACTISCAN_API_BASE = "{{ env('ACTISCAN_API_BASE', '/api-bridge') }}";
    </script>
    <script src="{{ asset('js/actiscan-api.js') }}"></script>
    @yield('scripts')
</body>
</html>
