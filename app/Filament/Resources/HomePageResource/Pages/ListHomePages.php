<?php

namespace App\Filament\Resources\HomePageResource\Pages;

use App\Filament\Resources\HomePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Redirect;
use App\Models\HomePage;

class ListHomePages extends ListRecords
{
    protected static string $resource = HomePageResource::class;

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
        // Tek ana sayfa kaydını al
        $record = HomePage::getSettings();
        
        // Direkt edit sayfasına yönlendir
        Redirect::to(HomePageResource::getUrl('edit', ['record' => $record->id]))->send();
    }
}
