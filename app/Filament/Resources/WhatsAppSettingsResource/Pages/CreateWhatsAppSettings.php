<?php

namespace App\Filament\Resources\WhatsAppSettingsResource\Pages;

use App\Filament\Resources\WhatsAppSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;

class CreateWhatsAppSettings extends CreateRecord
{
    protected static string $resource = WhatsAppSettingsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Cache'e kaydet
        Cache::put('whatsapp_settings', $data, now()->addDays(30));
        
        Notification::make()
            ->title('WhatsApp ayarları başarıyla kaydedildi')
            ->success()
            ->send();
        
        return $data;
    }
}
