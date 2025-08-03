<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Redirect;
use App\Models\Event;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        // Yeni kayıt oluşturma butonu kaldırıldı
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        // Sadece ilk kaydı getir
        return parent::getTableQuery()?->limit(1);
    }

    public function mount(): void
    {
        // Tek etkinlikler sayfası kaydını al
        $record = Event::getSettings();
        
        // Direkt edit sayfasına yönlendir
        Redirect::to(EventResource::getUrl('edit', ['record' => $record->id]))->send();
    }
}
