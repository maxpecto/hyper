<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avtomobillər - Hyper Drive</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('components.navigation')

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">
                <span class="glitch" data-text="AVTOMOBİLLƏR">AVTOMOBİLLƏR</span>
            </h1>
            <p class="page-subtitle">Cəmiyyətimizdə cyberpunk avtomobil kolleksiyası</p>
        </div>
    </section>

    <!-- Cars Filter -->
    <section class="cars-filter">
        <div class="container">
            <div class="filter-controls">
                <div class="filter-buttons">
                    <button class="filter-btn {{ $category === 'all' ? 'active' : '' }}" data-filter="all">Hamısı</button>
                    <button class="filter-btn {{ $category === 'bmw' ? 'active' : '' }}" data-filter="bmw">BMW</button>
                    <button class="filter-btn {{ $category === 'mercedes' ? 'active' : '' }}" data-filter="mercedes">Mercedes</button>
                    <button class="filter-btn {{ $category === 'audi' ? 'active' : '' }}" data-filter="audi">Audi</button>
                    <button class="filter-btn {{ $category === 'japanese' ? 'active' : '' }}" data-filter="japanese">Japon</button>
                    <button class="filter-btn {{ $category === 'other' ? 'active' : '' }}" data-filter="other">Diğer</button>
                </div>
                <div class="search-box">
                    <input type="text" id="carSearch" placeholder="Araç ara..." value="{{ $search }}">
                    <button class="search-btn">🔍</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Cars Gallery -->
    <section class="cars-gallery">
        <div class="container">
            <div class="cars-grid">
                @forelse($cars as $car)
                <div class="car-card" data-category="{{ $car->category }}">
                    <div class="car-image">
                        <img src="{{ $car->main_image ? asset('storage/' . $car->main_image) : 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=500&h=300&fit=crop' }}" alt="{{ $car->title }}">
                        <div class="car-overlay">
                            <div class="car-stats">
                                <div class="stat">⚡ {{ $car->horsepower }}</div>
                                <div class="stat">🏎️ {{ $car->acceleration }}</div>
                            </div>
                            <button class="view-details" data-car-id="{{ $car->id }}">Detayları Gör</button>
                        </div>
                    </div>
                    <div class="car-info">
                        <div class="car-header">
                            <h3 class="car-title">{{ $car->title }}</h3>
                            <div class="car-rating">
                                <span class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $car->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                                <span class="rating-count">({{ $car->rating_count }})</span>
                            </div>
                        </div>
                        <div class="car-owner">
                            <img src="{{ $car->owner_avatar ? asset('storage/' . $car->owner_avatar) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face' }}" alt="Owner">
                            <span>{{ $car->owner_username }}</span>
                        </div>
                        <div class="car-specs">
                            <div class="spec-item">
                                <span class="spec-label">Yıl</span>
                                <span class="spec-value">{{ $car->year }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Renk</span>
                                <span class="spec-value">{{ $car->color }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Modifikasyon</span>
                                <span class="spec-value">{{ $car->modification }}</span>
                            </div>
                        </div>
                        <div class="car-tags">
                            @foreach($car->tags as $tag)
                            <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-cars-message">
                    <div style="text-align: center; padding: 3rem; color: var(--text-gray); font-size: 1.2rem;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🚗</div>
                        <p>Henüz araç bulunmuyor.</p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">İlk araç siz olun!</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Add Your Car CTA -->
    <section class="add-car-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Araçını Sergile</h2>
                <p>Cyberpunk koleksiyonuna araçını ekle ve topluluğun beğenisini kazan!</p>
                <a href="{{ route('register') }}" class="btn-primary">Araç Ekle</a>
            </div>
        </div>
    </section>

    @include('components.footer')

    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('cars.js') }}"></script>
</body>
</html> 