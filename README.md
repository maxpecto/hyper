## Proje Hakkında

Bu sistem aslında bir araba etkinliği için tasarlandı. Katılımcılar arabalarını kaydediyor, admin onaylıyor ve sonra herkes oy veriyor.

## Ne Yapıyor?

### Araba Kayıt Sistemi
- Kullanıcılar arabalarının 6 farklı fotoğrafını yüklüyor (ön, arka, sol, sağ, iç mekan, motor)
- Araba bilgileri, deneyim yılı, ilgi alanları gibi detayları giriyorlar
- Form hem tarayıcıda hem sunucuda kontrol ediliyor
- Azerbaycan dilinde

### Oylama Sistemi
- Her kullanıcı sadece bir kez oy verebiliyor (benzersiz kod sistemi)
- Admin onayladıktan sonra arabalar oylamaya katılabiliyor
- Canlı istatistikler görülebiliyor
- Cookie sistemi ile kullanıcı deneyimi iyileştirildi

### Admin Paneli
- Filament kullanarak güzel bir admin paneli
- Kayıtları onaylama/reddetme
- WhatsApp entegrasyonu (otomatik mesajlar)
- Oylama kodları oluşturma
- Detaylı raporlar

## Kullandığımız Teknolojiler

### Backend
- **Laravel 12.21.0** - PHP framework'ü
- **SQLite** - Veritabanı (geliştirme için pratik)
- **Filament 3.3.34** - Admin panel

### Frontend
- **HTML5/CSS3** - Responsive tasarım
- **JavaScript (ES6+)** - Dinamik işlemler
- **AJAX** - Sayfa yenilemeden veri alışverişi

### Diğer
- **Blade Templating** - Laravel'in template sistemi
- **Eloquent ORM** - Veritabanı işlemleri
- **Composer** - PHP paket yöneticisi

## Kurulum

### Gereksinimler
- PHP 8.1 veya üzeri
- Composer
- Node.js (opsiyonel)

### Hızlı Kurulum

1. **Projeyi indirin**
```bash
git clone https://github.com/maxpecto/hyper.git
cd hyper
```

2. **Bağımlılıkları yükleyin**
```bash
composer install
npm install
```

3. **Ortam ayarları**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Veritabanını hazırlayın**
```bash
php artisan migrate
php artisan db:seed
```

5. **Storage linkini oluşturun**
```bash
php artisan storage:link
```

6. **Sunucuyu başlatın**
```bash
php artisan serve
```

**Not**: Eğer otomatik kurulum scripti kullanmak isterseniz:
```bash
chmod +x setup.sh
./setup.sh
```

## Nasıl Kullanılır?

### Kullanıcı Tarafı

#### Araba Kaydı
1. `/register` sayfasına gidin
2. Formu doldurun ve 6 fotoğraf yükleyin
3. Gönderin ve admin onayını bekleyin

#### Oylama
1. `/voting` sayfasına gidin
2. Size verilen kodu girin
3. Beğendiğiniz arabaya oy verin

### Admin Tarafı

#### Admin Paneline Giriş
- `/admin` adresine gidin
- E-posta: `admin@example.com`
- Şifre: `password`

#### Kayıtları Yönetin
- **Registrations** bölümünde kayıtları görün
- Onaylayın veya reddedin
- WhatsApp bildirimleri otomatik gönderilir

#### Oylama Kodları Oluşturun
- **WhatsApp Settings** bölümünde
- "Generate Codes" butonuna tıklayın
- İstediğiniz sayıda kod oluşturun

## API'ler

### Oylama API'leri

#### Kod Doğrulama
```http
POST /voting/verify-code
Content-Type: application/json

{
    "code": "ABC123"
}
```

**Başarılı yanıt:**
```json
{
    "success": true,
    "message": "Kod təsdiqləndi",
    "code_id": 1
}
```

#### Oy Verme
```http
POST /voting/vote
Content-Type: application/json

{
    "code_id": 1,
    "registration_id": 5
}
```

**Başarılı yanıt:**
```json
{
    "success": true,
    "message": "Oy uğurla qeydə alındı",
    "stats": {
        "total_votes": 150,
        "used_codes": 45
    }
}
```

#### İstatistikler
```http
GET /voting/stats
```

**Yanıt:**
```json
{
    "total_votes": 150,
    "total_cars": 25,
    "used_codes": 45,
    "cars": [
        {
            "id": 1,
            "votes": 15,
            "percentage": 10.0
        }
    ]
}
```

## Veritabanı Yapısı

### Tablolar

#### `registrations` - Kayıtlar
- `id` (Primary Key)
- `full_name` (Ad soyad)
- `phone` (Telefon)
- `email` (E-posta)
- `car_brand` (Araba markası)
- `car_model` (Araba modeli)
- `car_year` (Yıl)
- `engine_size` (Motor hacmi)
- `modifications` (Modifikasyonlar)
- `experience_years` (Deneyim yılı)
- `interests` (İlgi alanları - JSON)
- `photo_front`, `photo_back`, `photo_left`, `photo_right`, `photo_interior`, `photo_engine` (Fotoğraflar)
- `status` (Durum: pending, approved, rejected)
- `admin_notes` (Admin notları)
- `newsletter_subscription` (Bülten aboneliği)
- `created_at`, `updated_at` (Zaman damgaları)

#### `voting_codes` - Oylama Kodları
- `id` (Primary Key)
- `code` (Kod - benzersiz)
- `is_used` (Kullanıldı mı?)
- `used_at` (Kullanım zamanı)
- `used_ip` (Kullanılan IP)
- `created_at`, `updated_at`

#### `votes` - Oy Kayıtları
- `id` (Primary Key)
- `voting_code_id` (Kod ID'si)
- `registration_id` (Kayıt ID'si)
- `ip_address` (IP adresi)
- `user_agent` (Tarayıcı bilgisi)
- `created_at`, `updated_at`

#### `whats_app_settings` - WhatsApp Ayarları
- `id` (Primary Key)
- `whatsapp_enabled` (Aktif mi?)
- `whatsapp_approved_message` (Onay mesajı)
- `whatsapp_rejected_message` (Red mesajı)
- `whatsapp_pending_message` (Bekleme mesajı)
- `created_at`, `updated_at`

## Dosya Yapısı

```
hyperdrive/
├── app/
│   ├── Filament/                    # Admin panel
│   │   ├── Resources/              # Model yönetimi
│   │   └── Pages/                  # Özel sayfalar
│   ├── Http/Controllers/           # Controller'lar
│   └── Models/                     # Model'ler
├── database/
│   ├── migrations/                 # Veritabanı şemaları
│   └── seeders/                   # Test verileri
├── public/                         # Statik dosyalar
│   ├── css/                        # Stil dosyaları
│   ├── js/                         # JavaScript dosyaları
│   └── storage/                    # Yüklenen dosyalar
└── resources/
    └── views/                      # Blade template'leri
```

## Önemli Dosyalar

### Frontend
- `public/register.js` - Kayıt formu işlemleri
- `public/voting.js` - Oylama sistemi
- `public/style.css` - Ana stil dosyası

### Backend
- `app/Http/Controllers/RegistrationController.php` - Kayıt işlemleri
- `app/Http/Controllers/VotingController.php` - Oylama API'leri
- `app/Models/Registration.php` - Kayıt modeli
- `app/Models/VotingCode.php` - Kod modeli

### Admin Panel
- `app/Filament/Resources/RegistrationResource.php` - Kayıt yönetimi
- `app/Filament/Resources/WhatsAppSettingsResource.php` - WhatsApp ayarları

## Güvenlik

1. **CSRF Koruması** - Tüm formlarda CSRF token var
2. **Input Validasyonu** - Hem tarayıcıda hem sunucuda kontrol
3. **File Upload Güvenliği** - Sadece resim dosyaları kabul ediliyor
4. **SQL Injection Koruması** - Eloquent ORM kullanıyoruz
5. **XSS Koruması** - Blade template escaping

## Performans

1. **Database Indexing** - Sık sorgulanan alanlar için index
2. **Caching** - WhatsApp ayarları cache'leniyor
3. **Image Optimization** - Yüklenen resimler optimize ediliyor
4. **Lazy Loading** - Büyük listeler için sayfalama

## Sorun Giderme

### Yaygın Sorunlar

#### "500 Internal Server Error"
```bash
# Log dosyalarını kontrol edin
tail -f storage/logs/laravel.log
```

#### "Storage link not found"
```bash
php artisan storage:link
```

#### "Database table not found"
```bash
php artisan migrate:fresh
```

#### "Permission denied"
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Debug Modu
```bash
# .env dosyasında
APP_DEBUG=true
APP_ENV=local
```

## Önemli Bilgiler

- **Admin Panel**: http://localhost:8000/admin
- **Admin E-posta**: admin@example.com
- **Admin Şifre**: password
- **Kayıt Sayfası**: http://localhost:8000/register
- **Oylama Sayfası**: http://localhost:8000/voting

## İletişim
