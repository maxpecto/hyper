<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Səsvermə - Hyper Drive</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-menu nav-menu-left">
                <a href="index.html" class="nav-link">Ana Səhifə</a>
                <a href="events.html" class="nav-link">Tədbirlər</a>
                <a href="cars.html" class="nav-link">Avtomobillər</a>
            </div>
            <div class="nav-logo">
                <span class="logo-text">HYPER DRIVE</span>
            </div>
            <div class="nav-menu nav-menu-right">
                <a href="{{ route('voting.show') }}" class="nav-link active">Səsvermə</a>
                <a href="sponsors.html" class="nav-link">Sponsorlar</a>
                <a href="{{ route('register') }}" class="nav-link cta-link">Qoşul</a>
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
                <span class="glitch" data-text="SƏSVERMƏ">SƏSVERMƏ</span>
            </h1>
            <p class="page-subtitle">Ayın ən yaxşı cyberpunk avtomobilləri üçün səs ver</p>
        </div>
    </section>

    <!-- Voting Stats -->
    <section class="voting-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card-voting">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-info">
                        <h3 id="totalVotes">{{ $stats['total_votes'] ?? 0 }}</h3>
                        <p>Ümumi Səs</p>
                    </div>
                </div>
                <div class="stat-card-voting">
                    <div class="stat-icon">🚗</div>
                    <div class="stat-info">
                        <h3 id="totalCars">{{ $stats['total_cars'] ?? 0 }}</h3>
                        <p>Yarışan Araç</p>
                    </div>
                </div>
                <div class="stat-card-voting">
                    <div class="stat-icon">🔑</div>
                    <div class="stat-info">
                        <h3 id="usedCodes">{{ $stats['used_codes'] ?? 0 }}</h3>
                        <p>İstifadə Edilən Kod</p>
                    </div>
                </div>
                <div class="stat-card-voting">
                    <div class="stat-icon">🎁</div>
                    <div class="stat-info">
                        <h3>₺5,000</h3>
                        <p>Ödül Havuzu</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Code Verification Modal -->
    <div id="codeModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Oy Kullanma Kodu</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>Oy vermək üçün kodunuzu daxil edin:</p>
                <div class="form-group">
                    <input type="text" id="votingCode" placeholder="Kodunuzu daxil edin" maxlength="10">
                    <div class="form-glow"></div>
                </div>
                <div id="codeMessage" class="message"></div>
                <button id="verifyCodeBtn" class="btn-primary">Kodu Təsdiqlə</button>
            </div>
        </div>
    </div>

    <!-- Voting Cars -->
    <section class="voting-cars">
        <div class="container">
            <div class="voting-grid" id="votingGrid">
                @forelse($approvedCars as $index => $car)
                <div class="voting-card {{ $index === 0 ? 'leader' : '' }}" data-car-id="{{ $car->id }}">
                    <div class="voting-position">
                        <span class="position-number">{{ $index + 1 }}</span>
                        @if($index === 0)
                        <span class="crown">👑</span>
                        @endif
                    </div>
                    <div class="car-image-voting">
                        @if($car->front_photo)
                            <img src="{{ asset('storage/' . $car->front_photo) }}" alt="{{ $car->car_brand }} {{ $car->car_model }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&h=400&fit=crop" alt="{{ $car->car_brand }} {{ $car->car_model }}">
                        @endif
                        <div class="neon-glow"></div>
                    </div>
                    <div class="voting-info">
                        <h3 class="car-title">{{ $car->car_brand }} {{ $car->car_model }}</h3>
                        <div class="car-owner-voting">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Owner">
                            <span>@{{ $car->username }}</span>
                        </div>
                        <div class="vote-count">
                            <span class="votes" data-car-id="{{ $car->id }}">{{ $car->vote_count }}</span>
                            <span class="vote-label">oy</span>
                        </div>
                        <div class="vote-percentage">
                            <div class="percentage-bar">
                                <div class="percentage-fill" style="width: {{ $car->vote_percentage }}%"></div>
                            </div>
                            <span class="percentage-text">{{ $car->vote_percentage }}%</span>
                        </div>
                        <button class="vote-btn" data-car-id="{{ $car->id }}">
                            <span class="vote-icon">❤️</span>
                            <span class="vote-text">Oy Ver</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="no-cars-message">
                    <h3>Hələ heç bir avtomobil təsdiqlənməyib</h3>
                    <p>Oy verməyə başlamaq üçün avtomobillərin təsdiqlənməsini gözləyin.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Voting Rules -->
    <section class="voting-rules">
        <div class="container">
            <div class="rules-container">
                <h2 class="section-title">Oylama Kuralları</h2>
                <div class="rules-content">
                    <div class="rule-item">
                        <div class="rule-icon">🔐</div>
                        <div class="rule-text">
                            <h3>Kod İlə Oy Vermə</h3>
                            <p>Oy vermək üçün xüsusi kod tələb olunur. Hər kod yalnız bir dəfə istifadə edilə bilər.</p>
                        </div>
                    </div>
                    <div class="rule-item">
                        <div class="rule-icon">📅</div>
                        <div class="rule-text">
                            <h3>Aylıq Oylama</h3>
                            <p>Hər ay yeni bir oylama düzənlənir və qaliblər mükafatlandırılır.</p>
                        </div>
                    </div>
                    <div class="rule-item">
                        <div class="rule-icon">⚡</div>
                        <div class="rule-text">
                            <h3>Bir Oy Hüququ</h3>
                            <p>Hər kod yalnız bir avtomobilə oy verə bilər. Oy dəyişdirmək mümkün deyil.</p>
                        </div>
                    </div>
                    <div class="rule-item">
                        <div class="rule-icon">🏆</div>
                        <div class="rule-text">
                            <h3>Mükafatlar</h3>
                            <p>İlk 3 sıra pul mükafatı qazanır, digər iştirakçılar xüsusi nişan alır.</p>
                        </div>
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
                    <p>Gelecek burada. Arabanla tanışma zamanı.</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>Hızlı Erişim</h4>
                        <a href="events.html">Etkinlikler</a>
                        <a href="cars.html">Otomobiller</a>
                        <a href="{{ route('voting.show') }}">Oylama</a>
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
    <script src="{{ asset('voting.js') }}"></script>
</body>
</html> 