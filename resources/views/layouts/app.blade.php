<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Explant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Cr4+r8mV7E6KjL1PjIuFBo8zpq7wcmI7NY+qd7t3Kh1qI2tWPNWs9TzXH7dKSUg77Km3gHAGeA+8U45mclCy5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    @include('header-footer.header')

    <!-- Konten Utama -->
    @yield('content')

    <!-- Footer -->
    @include('header-footer.footer')

    @stack('scripts')

</body>

</html>
