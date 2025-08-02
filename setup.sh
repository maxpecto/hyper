#!/bin/bash

# 🏎️ HyperDrive ParkMeet25 - Kurulum Scripti
# Bu script projeyi hızlıca kurar ve çalışır hale getirir

echo "🚀 HyperDrive ParkMeet25 kurulumu başlıyor..."

# Renkli çıktı için fonksiyonlar
print_success() {
    echo -e "\033[32m✅ $1\033[0m"
}

print_error() {
    echo -e "\033[31m❌ $1\033[0m"
}

print_info() {
    echo -e "\033[34mℹ️  $1\033[0m"
}

print_warning() {
    echo -e "\033[33m⚠️  $1\033[0m"
}

# Gereksinimleri kontrol et
check_requirements() {
    print_info "Gereksinimler kontrol ediliyor..."
    
    # PHP kontrolü
    if ! command -v php &> /dev/null; then
        print_error "PHP bulunamadı! Lütfen PHP 8.1+ yükleyin."
        exit 1
    fi
    
    # Composer kontrolü
    if ! command -v composer &> /dev/null; then
        print_error "Composer bulunamadı! Lütfen Composer yükleyin."
        exit 1
    fi
    
    # Node.js kontrolü (opsiyonel)
    if ! command -v node &> /dev/null; then
        print_warning "Node.js bulunamadı. NPM paketleri yüklenmeyecek."
    fi
    
    print_success "Gereksinimler kontrol edildi!"
}

# Bağımlılıkları yükle
install_dependencies() {
    print_info "PHP bağımlılıkları yükleniyor..."
    composer install --no-interaction
    
    if command -v node &> /dev/null; then
        print_info "NPM bağımlılıkları yükleniyor..."
        npm install
    fi
    
    print_success "Bağımlılıklar yüklendi!"
}

# Ortam dosyasını ayarla
setup_environment() {
    print_info "Ortam dosyası ayarlanıyor..."
    
    if [ ! -f .env ]; then
        cp .env.example .env
        print_success ".env dosyası oluşturuldu!"
    else
        print_info ".env dosyası zaten mevcut."
    fi
    
    # Application key oluştur
    php artisan key:generate --no-interaction
    print_success "Application key oluşturuldu!"
}

# Veritabanını hazırla
setup_database() {
    print_info "Veritabanı hazırlanıyor..."
    
    # Migration'ları çalıştır
    php artisan migrate --force
    
    # Varsayılan admin kullanıcısı oluştur
    php artisan tinker --execute="
        if (!App\Models\User::where('email', 'admin@example.com')->exists()) {
            App\Models\User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            echo 'Admin kullanıcısı oluşturuldu!\n';
        }
    "
    
    # WhatsApp ayarları için varsayılan kayıt oluştur
    php artisan tinker --execute="
        if (!App\Models\WhatsAppSetting::exists()) {
            App\Models\WhatsAppSetting::create([
                'whatsapp_enabled' => true,
                'whatsapp_approved_message' => 'Müraciətiniz təsdiqləndi! Oylamaya qatıla bilərsiniz.',
                'whatsapp_rejected_message' => 'Müraciətiniz rədd edildi. Daha sonra yenidən müraciət edə bilərsiniz.',
                'whatsapp_pending_message' => 'Müraciətiniz gözləyir. Tezliklə nəticəni öyrənəcəksiniz.',
            ]);
            echo 'WhatsApp ayarları oluşturuldu!\n';
        }
    "
    
    print_success "Veritabanı hazırlandı!"
}

# Storage linkini oluştur
setup_storage() {
    print_info "Storage linki oluşturuluyor..."
    php artisan storage:link
    print_success "Storage linki oluşturuldu!"
}

# İzinleri ayarla
setup_permissions() {
    print_info "Dosya izinleri ayarlanıyor..."
    
    # Storage klasörü için yazma izni
    chmod -R 755 storage/
    chmod -R 755 bootstrap/cache/
    
    print_success "İzinler ayarlandı!"
}

# Cache'i temizle
clear_cache() {
    print_info "Cache temizleniyor..."
    
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    
    print_success "Cache temizlendi!"
}

# Test verileri oluştur (opsiyonel)
create_test_data() {
    read -p "Test verileri oluşturmak istiyor musunuz? (y/N): " -n 1 -r
    echo
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_info "Test verileri oluşturuluyor..."
        
        # Örnek araba kayıtları
        php artisan tinker --execute="
            \$cars = [
                ['full_name' => 'Əli Məmmədov', 'phone' => '+994501234567', 'email' => 'ali@example.com', 'car_brand' => 'BMW', 'car_model' => 'M3', 'car_year' => 2020, 'engine_size' => '3.0L', 'modifications' => 'Sport egzoz', 'experience_years' => 5, 'status' => 'approved'],
                ['full_name' => 'Aysu Əliyeva', 'phone' => '+994507654321', 'email' => 'aysu@example.com', 'car_brand' => 'Mercedes', 'car_model' => 'C63', 'car_year' => 2021, 'engine_size' => '4.0L', 'modifications' => 'Tuning', 'experience_years' => 3, 'status' => 'approved'],
                ['full_name' => 'Rəşad Hüseynov', 'phone' => '+994509876543', 'email' => 'rashad@example.com', 'car_brand' => 'Audi', 'car_model' => 'RS6', 'car_year' => 2019, 'engine_size' => '4.0L', 'modifications' => 'Body kit', 'experience_years' => 7, 'status' => 'approved'],
            ];
            
            foreach (\$cars as \$car) {
                App\Models\Registration::create(\$car);
            }
            echo 'Test arabaları oluşturuldu!\n';
        "
        
        # Örnek oylama kodları
        php artisan tinker --execute="
            for (\$i = 1; \$i <= 10; \$i++) {
                App\Models\VotingCode::create([
                    'code' => 'TEST' . str_pad(\$i, 3, '0', STR_PAD_LEFT),
                    'is_used' => false,
                ]);
            }
            echo 'Test oylama kodları oluşturuldu!\n';
        "
        
        print_success "Test verileri oluşturuldu!"
    fi
}

# Kurulum tamamlandı mesajı
show_completion_message() {
    echo
    echo "🎉 Kurulum tamamlandı!"
    echo
    echo "📋 Önemli Bilgiler:"
    echo "   • Admin Panel: http://localhost:8000/admin"
    echo "   • Admin E-posta: admin@example.com"
    echo "   • Admin Şifre: password"
    echo
    echo "🌐 Sayfalar:"
    echo "   • Ana Sayfa: http://localhost:8000"
    echo "   • Kayıt: http://localhost:8000/register"
    echo "   • Oylama: http://localhost:8000/voting"
    echo
    echo "🚀 Sunucuyu başlatmak için:"
    echo "   php artisan serve"
    echo
    print_success "HyperDrive ParkMeet25 hazır!"
}

# Ana kurulum fonksiyonu
main() {
    echo "🏎️  HyperDrive ParkMeet25 - Kurulum Scripti"
    echo "=============================================="
    echo
    
    check_requirements
    install_dependencies
    setup_environment
    setup_database
    setup_storage
    setup_permissions
    clear_cache
    create_test_data
    show_completion_message
}

# Scripti çalıştır
main 