<?php

namespace App\Filament\Resources\WhatsAppSettingsResource\Pages;

use App\Filament\Resources\WhatsAppSettingsResource;
use App\Models\WhatsAppSetting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;

class EditWhatsAppSettings extends EditRecord
{
    protected static string $resource = WhatsAppSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Cache'e kaydet
        Cache::put('whatsapp_settings', $data, now()->addDays(30));
        
        Notification::make()
            ->title('WhatsApp ayarları başarıyla güncellendi')
            ->success()
            ->send();
        
        return $data;
    }
}
