<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hyper Drive - Avtomobil Cəmiyyəti</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-menu nav-menu-left">
                <a href="{{ route('home') }}" class="nav-link active">Ana Səhifə</a>
                <a href="{{ route('events') }}" class="nav-link">Tədbirlər</a>
                <a href="{{ route('cars') }}" class="nav-link">Avtomobillər</a>
            </div>
            <div class="nav-logo">
                <span class="logo-text">HYPER DRIVE</span>
            </div>
            <div class="nav-menu nav-menu-right">
                <a href="{{ route('voting') }}" class="nav-link">Səsvermə</a>
                <a href="{{ route('sponsors') }}" class="nav-link">Sponsorlar</a>
                <a href="{{ route('register') }}" class="nav-link cta-link">Qoşul</a>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-video">
            <!-- Desktop Video -->
            <iframe class="desktop-video"
                src="{{ $homeSettings->hero_video_desktop }}"
                frameborder="0" 
                allow="autoplay; encrypted-media" 
                allowfullscreen>
            </iframe>
            
            <!-- Mobile Video -->
            <iframe class="mobile-video"
                src="{{ $homeSettings->hero_video_mobile }}"
                frameborder="0" 
                allow="autoplay; encrypted-media" 
                allowfullscreen>
            </iframe>
            
            <div class="hero-overlay"></div>
        </div>
        
        <div class="hero-content" id="heroContent">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="glitch" data-text="{{ $homeSettings->hero_title }}">{{ $homeSettings->hero_title }}</span>
                </h1>
                <p class="hero-subtitle">{{ $homeSettings->hero_subtitle }}</p>
                <div class="hero-buttons">
                    <a href="{{ route('events') }}" class="btn-primary">Tədbirləri Kəşf Et</a>
                    <a href="{{ route('register.show') }}" class="btn-secondary">Cəmiyyətə Qoşul</a>
                </div>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($homeSettings->hero_stats_members) }}+</span>
                    <span class="stat-label">Üzv</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($homeSettings->hero_stats_events) }}+</span>
                    <span class="stat-label">Tədbir</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($homeSettings->hero_stats_cars) }}+</span>
                    <span class="stat-label">Avtomobil</span>
                </div>
            </div>
            
            <!-- Latest Event Quick Register -->
            <div class="hero-event-card">
                <div class="event-badge">
                    <img src="{{ asset($homeSettings->latest_event_image) }}" alt="{{ $homeSettings->latest_event_title }}" class="event-icon">
                </div>
                <div class="event-quick-info">
                    <div class="event-quick-title">{{ $homeSettings->latest_event_title }}</div>
                    <div class="event-quick-meta">
                        <span class="quick-date">{{ $homeSettings->latest_event_date }}</span>
                        <span class="quick-location">{{ $homeSettings->latest_event_location }}</span>
                        <span class="quick-spots">{{ $homeSettings->latest_event_spots }}</span>
                    </div>
                </div>
                <a href="{{ route('register.show') }}" class="btn-event-quick">
                    <span>QEYDİYYAT</span>
                    <div class="btn-glow"></div>
                </a>
            </div>
        </div>
        
        <div class="hero-toggle-btn" id="heroToggleBtn">
            <span class="toggle-icon">↑</span>
        </div>

        <div class="scroll-indicator">
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <h2 class="about-title">
                    <span class="glitch" data-text="{{ $homeSettings->about_title }}">{{ $homeSettings->about_title }}</span>
                </h2>
                <div class="about-text">
                    <p>{{ $homeSettings->about_description }}</p>
                </div>
                <div class="about-features">
                    @foreach($homeSettings->about_features as $feature)
                    <div class="feature-item">
                        <div class="feature-icon">{{ $feature['icon'] }}</div>
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="featured-events">
        <div class="container">
            <div class="events-content">
                <h2 class="section-title">{{ $homeSettings->featured_events_title }}</h2>
                <div class="events-grid">
                    @foreach($homeSettings->featured_events as $event)
                    <div class="event-card">
                        <div class="event-image">
                            <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}">
                            <div class="event-date">{{ $event['date'] }}</div>
                        </div>
                        <div class="event-content">
                            <h3>{{ $event['title'] }}</h3>
                            <p>{{ $event['description'] }}</p>
                            <div class="event-meta">
                                <span class="event-location">{{ $event['location'] }}</span>
                                <span class="event-participants">{{ $event['participants'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="section-cta">
                    <a href="{{ route('events') }}" class="btn-primary">Bütün Tədbirləri Gör</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Community Section -->
    <section class="community">
        <div class="container">
            <div class="community-content">
                <div class="community-text">
                    <h2 class="section-title">{{ $homeSettings->community_title }}</h2>
                    <p>{{ $homeSettings->community_description }}</p>
                    <ul class="feature-list">
                        @foreach($homeSettings->community_features as $feature)
                        <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register.show') }}" class="btn-primary">Üzv Ol</a>
                </div>
                <div class="community-stats">
                    <div class="stat-card">
                        <h3>{{ number_format($homeSettings->community_stats_members) }}+</h3>
                        <p>Aktiv Üzv</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ number_format($homeSettings->community_stats_events) }}+</h3>
                        <p>Aylıq Tədbir</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ number_format($homeSettings->community_stats_cars) }}+</h3>
                        <p>Qeydiyyatlı Avtomobil</p>
                    </div>
                    <div class="stat-card">
                        <h3>{{ number_format($homeSettings->community_stats_brands) }}+</h3>
                        <p>Müxtəlif Marka</p>
                    </div>
                </div>
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
                    <p>{{ $homeSettings->footer_description }}</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>Sürətli Giriş</h4>
                        <a href="{{ route('events') }}">Tədbirlər</a>
                        <a href="{{ route('cars') }}">Avtomobillər</a>
                        <a href="{{ route('voting.show') }}">Səsvermə</a>
                        <a href="{{ route('register.show') }}">Üzv Ol</a>
                    </div>
                    <div class="link-group">
                        <h4>Əlaqə</h4>
                        <a href="mailto:{{ $homeSettings->footer_email }}">{{ $homeSettings->footer_email }}</a>
                        @if($homeSettings->footer_instagram)
                        <a href="{{ $homeSettings->footer_instagram }}">Instagram</a>
                        @endif
                        <a href="#">{{ $homeSettings->footer_location }}</a>
                    </div>
                    <div class="link-group">
                        <h4>Sosyal</h4>
                        @if($homeSettings->footer_instagram)
                        <a href="{{ $homeSettings->footer_instagram }}">Instagram</a>
                        @endif
                        @if($homeSettings->footer_tiktok)
                        <a href="{{ $homeSettings->footer_tiktok }}">TikTok</a>
                        @endif
                        @if($homeSettings->footer_youtube)
                        <a href="{{ $homeSettings->footer_youtube }}">YouTube</a>
                        @endif
                        @if($homeSettings->footer_telegram)
                        <a href="{{ $homeSettings->footer_telegram }}">Telegram</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Hyper Drive. Bütün hüquqlar qorunur.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('script.js') }}"></script>
</body>
</html> 