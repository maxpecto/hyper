<?php

namespace App\Filament\Resources\WhatsAppSettingsResource\Pages;

use App\Filament\Resources\WhatsAppSettingsResource;
use App\Models\WhatsAppSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListWhatsAppSettings extends ListRecords
{
    protected static string $resource = WhatsAppSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Ayar')
                ->url(route('filament.admin.resources.whatsapp-settings.create')),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return WhatsAppSetting::query();
    }
}
