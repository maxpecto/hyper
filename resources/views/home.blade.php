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
                <a href="{{ route('home') }}" class="nav-link">Ana Sehife</a>
                <a href="{{ route('events') }}" class="nav-link">Tedbirler</a>
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
                    <a href="{{ route('register') }}" class="btn-secondary">Cəmiyyətə Qoşul</a>
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
                <a href="{{ route('register') }}" class="btn-event-quick">
                    <span class="btn-icon">🚗</span>
                    <span class="btn-text">Arabanı Qeydiyyatdan Keçir</span>
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
                    <a href="{{ route('register') }}" class="btn-primary">Üzv Ol</a>
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

    @include('components.footer')

    <script src="{{ asset('script.js') }}"></script>
</body>
</html> 