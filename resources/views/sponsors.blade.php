<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsorlar - Hyper Drive</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('components.navigation')

    <!-- Hero Section -->
    <section class="sponsors-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="glitch" data-text="SPONSORLAR">SPONSORLAR</span>
                </h1>
                <p class="hero-subtitle">HyperDrive Fest 2025 - Premium Partner Network</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-target="25">0</span>
                        <span class="stat-label">Premium Partner</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="200">0</span>
                        <span class="stat-label">İştirakçı</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="2500000">0</span>
                        <span class="stat-label">Sosial Media Baxışı</span>
                    </div>
                </div>
                <button class="cta-button">Partner Ol</button>
            </div>
        </div>
        <div class="particles"></div>
    </section>

    <!-- Platinum Sponsors -->
    @if($categories['platinum']->count() > 0)
    <section class="sponsors-section platinum-section">
        <div class="container">
            <h2 class="section-title">
                <span class="category-icon">👑</span>
                Platinum Sponsors
            </h2>
            <div class="sponsors-grid platinum-grid">
                @foreach($categories['platinum'] as $sponsor)
                <div class="sponsor-card platinum-card" data-sponsor-id="{{ $sponsor->id }}">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="logo-container">
                            @if($sponsor->logo)
                            <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
                            @else
                            <div class="logo-placeholder">{{ substr($sponsor->name, 0, 2) }}</div>
                            @endif
                        </div>
                        <h3 class="sponsor-name">{{ $sponsor->name }}</h3>
                        <div class="sponsor-category platinum-badge">Platinum</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Gold Sponsors -->
    @if($categories['gold']->count() > 0)
    <section class="sponsors-section gold-section">
        <div class="container">
            <h2 class="section-title">
                <span class="category-icon">🥇</span>
                Gold Sponsors
            </h2>
            <div class="sponsors-grid gold-grid">
                @foreach($categories['gold'] as $sponsor)
                <div class="sponsor-card gold-card" data-sponsor-id="{{ $sponsor->id }}">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="logo-container">
                            @if($sponsor->logo)
                            <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
                            @else
                            <div class="logo-placeholder">{{ substr($sponsor->name, 0, 2) }}</div>
                            @endif
                        </div>
                        <h3 class="sponsor-name">{{ $sponsor->name }}</h3>
                        <div class="sponsor-category gold-badge">Gold</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Silver Sponsors -->
    @if($categories['silver']->count() > 0)
    <section class="sponsors-section silver-section">
        <div class="container">
            <h2 class="section-title">
                <span class="category-icon">🥈</span>
                Silver Sponsors
            </h2>
            <div class="sponsors-grid silver-grid">
                @foreach($categories['silver'] as $sponsor)
                <div class="sponsor-card silver-card" data-sponsor-id="{{ $sponsor->id }}">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="logo-container">
                            @if($sponsor->logo)
                            <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
                            @else
                            <div class="logo-placeholder">{{ substr($sponsor->name, 0, 2) }}</div>
                            @endif
                        </div>
                        <h3 class="sponsor-name">{{ $sponsor->name }}</h3>
                        <div class="sponsor-category silver-badge">Silver</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Bronze Sponsors -->
    @if($categories['bronze']->count() > 0)
    <section class="sponsors-section bronze-section">
        <div class="container">
            <h2 class="section-title">
                <span class="category-icon">🥉</span>
                Bronze Sponsors
            </h2>
            <div class="sponsors-grid bronze-grid">
                @foreach($categories['bronze'] as $sponsor)
                <div class="sponsor-card bronze-card" data-sponsor-id="{{ $sponsor->id }}">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="logo-container">
                            @if($sponsor->logo)
                            <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
                            @else
                            <div class="logo-placeholder">{{ substr($sponsor->name, 0, 2) }}</div>
                            @endif
                        </div>
                        <h3 class="sponsor-name">{{ $sponsor->name }}</h3>
                        <div class="sponsor-category bronze-badge">Bronze</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-content">
                <h2 class="contact-title">BİRLİKDƏ YARADAQ!</h2>
                <p class="contact-text">HyperDrive Fest 2025'ə sponsor olun və markanızı minlərlə avtomobil həvəskarına tanıdın.</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h3>Südəf Əsgərov</h3>
                            <p>+994518281002</p>
                            <p>sudef.asgarov@hyperdrive.az</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">🌐</div>
                        <div class="contact-details">
                            <h3>Veb Sayt</h3>
                            <p>www.hyperdrive.az</p>
                            <p>Sosial media hesablarımızı izləyin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sponsor Modal -->
    <div class="sponsor-modal" id="sponsorModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <button class="modal-close">&times;</button>
            <div class="modal-body">
                <div class="modal-logo">
                    <img src="" alt="" id="modalLogo">
                </div>
                <h2 class="modal-title" id="modalTitle"></h2>
                <div class="modal-category" id="modalCategory"></div>
                <p class="modal-description" id="modalDescription"></p>
                <div class="modal-contact">
                    <div class="contact-links">
                        <a href="" id="modalWebsite" class="contact-link" target="_blank">
                            <span class="link-icon">🌐</span>
                            <span>Website</span>
                        </a>
                        <a href="" id="modalEmail" class="contact-link">
                            <span class="link-icon">📧</span>
                            <span>Email</span>
                        </a>
                        <a href="" id="modalPhone" class="contact-link">
                            <span class="link-icon">📞</span>
                            <span>Phone</span>
                        </a>
                    </div>
                    <div class="social-links">
                        <a href="" id="modalInstagram" class="social-link" target="_blank">
                            <span class="social-icon">📷</span>
                        </a>
                        <a href="" id="modalFacebook" class="social-link" target="_blank">
                            <span class="social-icon">📘</span>
                        </a>
                        <a href="" id="modalLinkedin" class="social-link" target="_blank">
                            <span class="social-icon">💼</span>
                        </a>
                    </div>
                </div>
                <div class="modal-partnership">
                    <span class="partnership-label">Partner Süresi:</span>
                    <span class="partnership-period" id="modalPartnership"></span>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')

    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('sponsors.js') }}"></script>
</body>
</html> 