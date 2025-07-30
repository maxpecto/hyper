<?php

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;

class ViewRegistration extends ViewRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('whatsapp')
                ->label('WhatsApp İlə Bildir')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(fn ($record) => $this->getWhatsAppUrl($record))
                ->openUrlInNewTab()
                ->visible(fn ($record) => $record->phone),
        ];
    }

    private function getWhatsAppUrl($record): string
    {
        $phone = $this->formatPhoneNumber($record->phone);
        
        // Ayarlardan mesaj şablonunu al
        $settings = cache()->get('whatsapp_settings', []);
        
        $message = $this->formatWhatsAppMessage($record, $settings);
        
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    private function formatWhatsAppMessage($record, array $settings): string
    {
        $name = $record->first_name . ' ' . $record->last_name;
        $status = $this->getStatusText($record->status);
        
        // Varsayılan mesajlar
        $defaultMessages = [
            'approved' => "Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Təsdiqləndi\n\n✅ Təbrik edirik! Başvurunuz təsdiqləndi.\n📞 Əlavə məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası",
            'rejected' => "Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Rədd edildi\n\n❌ Başvurunuz rədd edildi.\n📝 Səbəb: {reason}\n📞 Ətraflı məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası",
            'pending' => "Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Gözləyir\n\n⏳ Başvurunuz nəzərdən keçirilir.\n📞 Status barədə məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası"
        ];
        
        // Ayarlardan mesajı al veya varsayılanı kullan
        $template = $settings['whatsapp_' . $record->status . '_message'] ?? $defaultMessages[$record->status] ?? $defaultMessages['pending'];
        
        // Değişkenleri değiştir
        $variables = [
            'name' => $name,
            'status' => $status,
            'car_brand' => $record->car_brand,
            'car_model' => $record->car_model,
            'reason' => $record->admin_notes ?? 'Məlumat yoxdur'
        ];
        
        foreach ($variables as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        
        return $template;
    }

    private function formatPhoneNumber($phone): string
    {
        // Telefon numarasını temizle ve formatla
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Azerbaycan telefon numarası formatı
        if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
            return '994' . $phone;
        }
        
        if (strlen($phone) === 10 && substr($phone, 0, 2) === '05') {
            return '994' . substr($phone, 1);
        }
        
        if (strlen($phone) === 12 && substr($phone, 0, 3) === '994') {
            return $phone;
        }
        
        return $phone;
    }

    private function getStatusText($status): string
    {
        return match ($status) {
            'pending' => 'Gözləyir',
            'approved' => 'Təsdiqləndi',
            'rejected' => 'Rədd edildi',
            default => $status,
        };
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Şəxsi Məlumatlar')
                    ->schema([
                        TextEntry::make('first_name')
                            ->label('Ad'),
                        TextEntry::make('last_name')
                            ->label('Soyad'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('phone')
                            ->label('Telefon'),
                        TextEntry::make('username')
                            ->label('İstifadəçi Adı'),
                    ])->columns(2),

                Section::make('Avtomobil Məlumatları')
                    ->schema([
                        TextEntry::make('car_brand')
                            ->label('Marka'),
                        TextEntry::make('car_model')
                            ->label('Model'),
                        TextEntry::make('car_year')
                            ->label('İl'),
                        TextEntry::make('car_color')
                            ->label('Rəng'),
                        TextEntry::make('modifications')
                            ->label('Modifikasiyalar')
                            ->markdown(),
                    ])->columns(2),

                Section::make('Təcrübə və Maraq Sahələri')
                    ->schema([
                        TextEntry::make('experience')
                            ->label('Sürüş Təcrübəsi')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'beginner' => 'Başlanğıc (0-2 il)',
                                'intermediate' => 'Orta (3-5 il)',
                                'advanced' => 'İrəlidə (6-10 il)',
                                'expert' => 'Mütəxəssis (10+ il)',
                                default => $state,
                            }),
                        TextEntry::make('newsletter_subscription')
                            ->label('Bülleten Abunəliyi')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Bəli' : 'Xeyr'),
                    ])->columns(2),

                Section::make('Avtomobil Fotoşəkilləri')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('front_photo')
                                    ->label('Ön Görünüş')
                                    ->formatStateUsing(fn ($state, $record) => $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><img src="' . asset('storage/' . $state) . '" style="max-width: 200px; height: auto; border-radius: 8px;" /></a>' : 'Fotoğraf yoxdur')
                                    ->html(),
                                TextEntry::make('back_photo')
                                    ->label('Arxa Görünüş')
                                    ->formatStateUsing(fn ($state, $record) => $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><img src="' . asset('storage/' . $state) . '" style="max-width: 200px; height: auto; border-radius: 8px;" /></a>' : 'Fotoğraf yoxdur')
                                    ->html(),
                                TextEntry::make('left_photo')
                                    ->label('Sol Yan')
                                    ->formatStateUsing(fn ($state, $record) => $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><img src="' . asset('storage/' . $state) . '" style="max-width: 200px; height: auto; border-radius: 8px;" /></a>' : 'Fotoğraf yoxdur')
                                    ->html(),
                                TextEntry::make('right_photo')
                                    ->label('Sağ Yan')
                                    ->formatStateUsing(fn ($state, $record) => $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><img src="' . asset('storage/' . $state) . '" style="max-width: 200px; height: auto; border-radius: 8px;" /></a>' : 'Fotoğraf yoxdur')
                                    ->html(),
                                TextEntry::make('interior_photo')
                                    ->label('İç Görünüş')
                                    ->formatStateUsing(fn ($state, $record) => $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><img src="' . asset('storage/' . $state) . '" style="max-width: 200px; height: auto; border-radius: 8px;" /></a>' : 'Fotoğraf yoxdur')
                                    ->html(),
                                TextEntry::make('engine_photo')
                                    ->label('Mühərrik')
                                    ->formatStateUsing(fn ($state, $record) => $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><img src="' . asset('storage/' . $state) . '" style="max-width: 200px; height: auto; border-radius: 8px;" /></a>' : 'Fotoğraf yoxdur')
                                    ->html(),
                            ]),
                    ]),

                Section::make('Status və Qeydlər')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Gözləyir',
                                'approved' => 'Təsdiqləndi',
                                'rejected' => 'Rədd edildi',
                                default => $state,
                            }),
                        TextEntry::make('admin_notes')
                            ->label('Admin Qeydləri')
                            ->markdown(),
                        TextEntry::make('approved_at')
                            ->label('Təsdiqlənmə Tarixi')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('rejected_at')
                            ->label('Rədd Edilmə Tarixi')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('created_at')
                            ->label('Yaradılma Tarixi')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Yenilənmə Tarixi')
                            ->dateTime('d.m.Y H:i'),
                    ])->columns(2),
            ]);
    }
} 