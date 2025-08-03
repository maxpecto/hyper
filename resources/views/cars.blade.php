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
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-menu nav-menu-left">
                <a href="{{ route('home') }}" class="nav-link">Ana Sehife</a>
                <a href="{{ route('events') }}" class="nav-link">Tedbirler</a>
                <a href="{{ route('cars') }}" class="nav-link active">Avtomobiller</a>
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
            const carCards = document.querySelectorAll('.car-card');
            const searchInput = document.getElementById('carSearch');
            const searchBtn = document.querySelector('.search-btn');

            let currentFilter = '{{ $category }}';
            let currentSearch = '{{ $search }}';

            // Filter functionality
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    // Get filter value
                    currentFilter = button.getAttribute('data-filter');
                    
                    // Apply filters
                    applyFilters();
                    
                    // Update URL
                    updateURL();
                });
            });

            // Search functionality
            searchInput.addEventListener('input', (e) => {
                currentSearch = e.target.value.toLowerCase();
                debounceSearch();
            });

            searchBtn.addEventListener('click', () => {
                applyFilters();
                updateURL();
            });

            // Debounced search
            let searchTimeout;
            function debounceSearch() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                    updateURL();
                }, 300);
            }

            // Apply filters and search
            function applyFilters() {
                let visibleCount = 0;
                
                carCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    const carTitle = card.querySelector('.car-title').textContent.toLowerCase();
                    const carOwner = card.querySelector('.car-owner span').textContent.toLowerCase();
                    const carSpecs = Array.from(card.querySelectorAll('.spec-value')).map(spec => spec.textContent.toLowerCase()).join(' ');
                    const carTags = Array.from(card.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase()).join(' ');
                    
                    const searchableText = `${carTitle} ${carOwner} ${carSpecs} ${carTags}`;
                    
                    // Check filter
                    const passesFilter = currentFilter === 'all' || category === currentFilter;
                    
                    // Check search
                    const passesSearch = !currentSearch || searchableText.includes(currentSearch);
                    
                    if (passesFilter && passesSearch) {
                        showCard(card, visibleCount * 100);
                        visibleCount++;
                    } else {
                        hideCard(card);
                    }
                });
                
                // Show no results message
                updateResultsMessage(visibleCount);
            }

            // Show card with animation
            function showCard(card, delay = 0) {
                card.style.display = 'block';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                }, delay);
            }

            // Hide card with animation
            function hideCard(card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px) scale(0.9)';
                
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }

            // Update results message
            function updateResultsMessage(count) {
                let messageElement = document.querySelector('.results-message');
                
                if (count === 0) {
                    if (!messageElement) {
                        messageElement = document.createElement('div');
                        messageElement.className = 'results-message';
                        messageElement.style.cssText = `
                            text-align: center;
                            padding: 3rem;
                            color: var(--text-gray);
                            font-size: 1.2rem;
                            grid-column: 1 / -1;
                        `;
                        document.querySelector('.cars-grid').appendChild(messageElement);
                    }
                    
                    messageElement.innerHTML = `
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                        <p>Arama kriterlerinize uygun araç bulunamadı.</p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">Farklı filtreler veya arama terimleri deneyin.</p>
                    `;
                    messageElement.style.display = 'block';
                } else {
                    if (messageElement) {
                        messageElement.style.display = 'none';
                    }
                }
            }

            // Update URL
            function updateURL() {
                const params = new URLSearchParams();
                if (currentFilter !== 'all') {
                    params.set('category', currentFilter);
                }
                if (currentSearch) {
                    params.set('search', currentSearch);
                }
                
                const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.history.pushState({}, '', newURL);
            }

            // Initial filter application
            applyFilters();
        });
    </script>
</body>
</html> 