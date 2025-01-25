<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}" defer></script>
</head>
<body>
<header>
    @include('partials.header')
</header>
<main>
    @yield('content')
</main>
</body>
</html>
