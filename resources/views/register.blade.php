<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qeydiyyat - Hyper Drive</title>
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
                <a href="{{ route('register') }}" class="nav-link cta-link active">Qosul</a>
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
                <span class="glitch" data-text="QEYDİYYAT">QEYDİYYAT</span>
            </h1>
            <p class="page-subtitle">Hyper Drive cəmiyyətinə qoşul və gələcəyi yaxala</p>
        </div>
    </section>

    <!-- Registration Form -->
    <section class="registration-section">
        <div class="container">
            <div class="registration-container">
                <div class="registration-form">
                    <div class="form-header">
                        <h2>Cəmiyyətə Qoşul</h2>
                        <p>Cyberpunk avtomobil cəmiyyətinin bir hissəsi ol</p>
                    </div>

                    <form id="registrationForm" class="cyber-form" action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h3 class="section-title">Şəxsi Məlumatlar</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">Ad</label>
                                    <input type="text" id="firstName" name="firstName" required>
                                    <div class="form-glow"></div>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Soyad</label>
                                    <input type="text" id="lastName" name="lastName" required>
                                    <div class="form-glow"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">E-poçt</label>
                                    <input type="email" id="email" name="email" required>
                                    <div class="form-glow"></div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Telefon</label>
                                    <input type="tel" id="phone" name="phone" required>
                                    <div class="form-glow"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">İstifadəçi Adı</label>
                                <input type="text" id="username" name="username" required>
                                <div class="form-glow"></div>
                            </div>
                        </div>

                        <!-- Car Information -->
                        <div class="form-section">
                            <h3 class="section-title">Avtomobil Məlumatları</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="carBrand">Marka</label>
                                    <select id="carBrand" name="carBrand" required>
                                        <option value="">Seçin</option>
                                        <option value="bmw">BMW</option>
                                        <option value="mercedes">Mercedes-Benz</option>
                                        <option value="audi">Audi</option>
                                        <option value="volkswagen">Volkswagen</option>
                                        <option value="toyota">Toyota</option>
                                        <option value="honda">Honda</option>
                                        <option value="nissan">Nissan</option>
                                        <option value="subaru">Subaru</option>
                                        <option value="mitsubishi">Mitsubishi</option>
                                        <option value="other">Digər</option>
                                    </select>
                                    <div class="form-glow"></div>
                                </div>
                                <div class="form-group">
                                    <label for="carModel">Model</label>
                                    <input type="text" id="carModel" name="carModel" required>
                                    <div class="form-glow"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="carYear">Model Yılı</label>
                                    <select id="carYear" name="carYear" required>
                                        <option value="">Seçin</option>
                                        <option value="2024">2024</option>
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                        <option value="2020">2020</option>
                                        <option value="2019">2019</option>
                                        <option value="2018">2018</option>
                                        <option value="older">2018 və əvvəl</option>
                                    </select>
                                    <div class="form-glow"></div>
                                </div>
                                <div class="form-group">
                                    <label for="carColor">Rəng</label>
                                    <input type="text" id="carColor" name="carColor" required>
                                    <div class="form-glow"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="modifications">Modifikasiyalar</label>
                                <textarea id="modifications" name="modifications" rows="4" placeholder="Avtomobilinizdə etdiyiniz modifikasiyaları təsvir edin..."></textarea>
                                <div class="form-glow"></div>
                            </div>
                        </div>

                        <!-- Car Photos Section -->
                        <div class="form-section">
                            <h3 class="section-title">Avtomobil Fotoşəkilləri</h3>
                            <p class="section-description">Avtomobilinizin müxtəlif bucaqlardan fotoşəkillərini yükləyin (hər bucaqdan 1 fotoşəkil)</p>
                            
                            <div class="photo-upload-grid">
                                <div class="photo-upload-item">
                                    <label for="frontPhoto" class="photo-label">
                                        <div class="photo-preview" id="frontPreview">
                                            <div class="upload-placeholder">
                                                <span class="upload-icon">📷</span>
                                                <span class="upload-text">Ön Görünüş</span>
                                            </div>
                                        </div>
                                        <input type="file" id="frontPhoto" name="frontPhoto" accept="image/*" class="photo-input">
                                    </label>
                                </div>
                                
                                <div class="photo-upload-item">
                                    <label for="backPhoto" class="photo-label">
                                        <div class="photo-preview" id="backPreview">
                                            <div class="upload-placeholder">
                                                <span class="upload-icon">📷</span>
                                                <span class="upload-text">Arxa Görünüş</span>
                                            </div>
                                        </div>
                                        <input type="file" id="backPhoto" name="backPhoto" accept="image/*" class="photo-input">
                                    </label>
                                </div>
                                
                                <div class="photo-upload-item">
                                    <label for="leftPhoto" class="photo-label">
                                        <div class="photo-preview" id="leftPreview">
                                            <div class="upload-placeholder">
                                                <span class="upload-icon">📷</span>
                                                <span class="upload-text">Sol Yan</span>
                                            </div>
                                        </div>
                                        <input type="file" id="leftPhoto" name="leftPhoto" accept="image/*" class="photo-input">
                                    </label>
                                </div>
                                
                                <div class="photo-upload-item">
                                    <label for="rightPhoto" class="photo-label">
                                        <div class="photo-preview" id="rightPreview">
                                            <div class="upload-placeholder">
                                                <span class="upload-icon">📷</span>
                                                <span class="upload-text">Sağ Yan</span>
                                            </div>
                                        </div>
                                        <input type="file" id="rightPhoto" name="rightPhoto" accept="image/*" class="photo-input">
                                    </label>
                                </div>
                                
                                <div class="photo-upload-item">
                                    <label for="interiorPhoto" class="photo-label">
                                        <div class="photo-preview" id="interiorPreview">
                                            <div class="upload-placeholder">
                                                <span class="upload-icon">📷</span>
                                                <span class="upload-text">İç Görünüş</span>
                                            </div>
                                        </div>
                                        <input type="file" id="interiorPhoto" name="interiorPhoto" accept="image/*" class="photo-input">
                                    </label>
                                </div>
                                
                                <div class="photo-upload-item">
                                    <label for="enginePhoto" class="photo-label">
                                        <div class="photo-preview" id="enginePreview">
                                            <div class="upload-placeholder">
                                                <span class="upload-icon">📷</span>
                                                <span class="upload-text">Mühərrik</span>
                                            </div>
                                        </div>
                                        <input type="file" id="enginePhoto" name="enginePhoto" accept="image/*" class="photo-input">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Experience Level -->
                        <div class="form-section">
                            <h3 class="section-title">Təcrübə Səviyyəsi</h3>
                            <div class="form-group">
                                <label for="experience">Sürüş Təcrübəsi</label>
                                <select id="experience" name="experience" required>
                                    <option value="">Seçin</option>
                                    <option value="beginner">Başlanğıc (0-2 il)</option>
                                    <option value="intermediate">Orta (3-5 il)</option>
                                    <option value="advanced">İrəlidə (6-10 il)</option>
                                    <option value="expert">Mütəxəssis (10+ il)</option>
                                </select>
                                <div class="form-glow"></div>
                            </div>
                            <div class="form-group">
                                <label>Maraq Sahələri (İstəyə bağlı)</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="interests[]" value="racing">
                                        <span class="checkmark"></span>
                                        Yarış
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="interests[]" value="drift">
                                        <span class="checkmark"></span>
                                        Drift
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="interests[]" value="modification">
                                        <span class="checkmark"></span>
                                        Modifikasiya
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="interests[]" value="photography">
                                        <span class="checkmark"></span>
                                        Fotoqrafiya
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="interests[]" value="meetups">
                                        <span class="checkmark"></span>
                                        Görüşlər
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="interests[]" value="workshops">
                                        <span class="checkmark"></span>
                                        Emalatxanalar
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement -->
                        <div class="form-section">
                            <div class="form-group">
                                <label class="checkbox-label agreement">
                                    <input type="checkbox" name="agreement" required>
                                    <span class="checkmark"></span>
                                    <span class="agreement-text">
                                        <a href="#">İstifadə Şərtləri</a> və <a href="#">Gizlilik Siyasəti</a>'ni oxudum və qəbul edirəm.
                                    </span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="newsletter" value="1">
                                    <span class="checkmark"></span>
                                    E-poçt bülleteni və tədbir bildirişlərini almaq istəyirəm.
                                </label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary submit-btn">
                                <span class="btn-text">Qeydiyyatdan Keç</span>
                                <span class="btn-loader"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="registration-info">
                    <div class="info-card">
                        <h3>Nə Qazanırsınız?</h3>
                        <ul class="benefits-list">
                            <li>Eksklüzif tədbirlərə giriş</li>
                            <li>Peşəkar yarışçılardan təlim</li>
                            <li>Avtomobil modifikasiyası təlimatları</li>
                            <li>Cəmiyyət yarışları və mükafatlar</li>
                            <li>Pulsuz fotoşəkil çəkimi</li>
                            <li>Discord kanallarına giriş</li>
                        </ul>
                    </div>

                    <div class="stats-card">
                        <h3>Cəmiyyət Statistikası</h3>
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Aktiv Üzv</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Aylıq Tədbir</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">1000+</span>
                            <span class="stat-label">Qeydiyyatlı Avtomobil</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">25+</span>
                            <span class="stat-label">Fərqli Marka</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')

    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('register.js') }}"></script>
</body>
</html> 