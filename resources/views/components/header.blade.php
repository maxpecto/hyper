<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hyper Drive')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @yield('head')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-menu nav-menu-left">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Ana Səhifə</a>
                <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}">Tədbirlər</a>
                <a href="{{ route('cars') }}" class="nav-link {{ request()->routeIs('cars') ? 'active' : '' }}">Avtomobillər</a>
            </div>
            <div class="nav-logo">
                <span class="logo-text">HYPER DRIVE</span>
            </div>
            <div class="nav-menu nav-menu-right">
                <a href="{{ route('voting') }}" class="nav-link {{ request()->routeIs('voting') ? 'active' : '' }}">Səsvermə</a>
                <a href="{{ route('sponsors') }}" class="nav-link {{ request()->routeIs('sponsors') ? 'active' : '' }}">Sponsorlar</a>
                <a href="{{ route('register') }}" class="nav-link cta-link {{ request()->routeIs('register') ? 'active' : '' }}">Qoşul</a>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    @yield('content')

    @include('components.footer')

    <script src="{{ asset('script.js') }}"></script>
    @yield('scripts')
</body>
</html> 