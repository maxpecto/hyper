<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tədbirlər - Hyper Drive</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-menu nav-menu-left">
                <a href="{{ route('home') }}" class="nav-link">Ana Sehife</a>
                <a href="{{ route('events') }}" class="nav-link active">Tedbirler</a>
                <a href="{{ route('cars') }}" class="nav-link">Avtomobiller</a>
            </div>
            <div class="nav-logo">
                <span class="logo-text">HYPER DRIVE</span>
            </div>
            <div class="nav-menu nav-menu-right">
                <a href="{{ route('voting') }}" class="nav-link">Sesverme</a>
                <a href="{{ route('sponsors') }}" class="nav-link">Sponsorlar</a>
                <a href="{{ route('register') }}" class="nav-link cta-link">Qosul</a>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">
                <span class="glitch" data-text="{{ $settings->page_title }}">{{ $settings->page_title }}</span>
            </h1>
            <p class="page-subtitle">{{ $settings->page_subtitle }}</p>
        </div>
    </section>

    <!-- Events Filter -->
    <section class="events-filter">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">{{ $settings->filter_all_text }}</button>
                <button class="filter-btn" data-filter="racing">{{ $settings->filter_racing_text }}</button>
                <button class="filter-btn" data-filter="meet">{{ $settings->filter_meet_text }}</button>
                <button class="filter-btn" data-filter="drift">{{ $settings->filter_drift_text }}</button>
            </div>
        </div>
    </section>

    <!-- Events Grid -->
    <section class="events-section">
        <div class="container">
            <div class="events-grid">
                @foreach($settings->events as $event)
                <div class="event-card-detailed" data-category="{{ $event['category'] }}">
                    <div class="event-image">
                        <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}">
                        @if($event['is_featured'])
                        <div class="event-status featured">Önə Çıxan</div>
                        @endif
                        <div class="event-date">{{ $event['date'] }}</div>
                    </div>
                    <div class="event-content">
                        <div class="event-category">
                            @switch($event['category'])
                                @case('meet')
                                    {{ $settings->filter_meet_text }}
                                    @break
                                @case('racing')
                                    {{ $settings->filter_racing_text }}
                                    @break
                                @case('drift')
                                    {{ $settings->filter_drift_text }}
                                    @break
                                @default
                                    {{ ucfirst($event['category']) }}
                            @endswitch
                        </div>
                        <h3>{{ $event['title'] }}</h3>
                        <p class="event-description">{{ $event['description'] }}</p>
                        <div class="event-details">
                            <div class="detail-item">
                                <span class="detail-icon">📍</span>
                                <span>{{ $event['location'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">🕒</span>
                                <span>{{ $event['time'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">👥</span>
                                <span>{{ $event['participants'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">💰</span>
                                <span>{{ $event['price'] }}</span>
                            </div>
                        </div>
                        <div class="event-actions">
                            <a href="{{ $event['registration_url'] }}" class="btn-primary">Qeydiyyat</a>
                            <a href="{{ $event['details_url'] }}" class="btn-secondary">Təfərrüatlar</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <span>HYPER DRIVE</span>
                    </div>
                    <p>Gelecek burada. Arabanla tanışma zamanı.</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>Hızlı Erişim</h4>
                        <a href="{{ route('events') }}">Etkinlikler</a>
                        <a href="{{ route('cars') }}">Otomobiller</a>
                        <a href="{{ route('voting') }}">Oylama</a>
                        <a href="{{ route('register') }}">Üye Ol</a>
                    </div>
                    <div class="link-group">
                        <h4>İletişim</h4>
                        <a href="#">info@hyperdrive.com</a>
                        <a href="#">+90 (212) 555-0123</a>
                        <a href="#">İstanbul, Türkiye</a>
                    </div>
                    <div class="link-group">
                        <h4>Sosyal</h4>
                        <a href="#">Instagram</a>
                        <a href="#">YouTube</a>
                        <a href="#">Discord</a>
                        <a href="#">TikTok</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Hyper Drive. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('script.js') }}"></script>
    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const eventCards = document.querySelectorAll('.event-card-detailed');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    eventCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html> 