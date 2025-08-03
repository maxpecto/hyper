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