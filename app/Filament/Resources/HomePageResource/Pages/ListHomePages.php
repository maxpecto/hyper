<?php

namespace App\Filament\Resources\HomePageResource\Pages;

use App\Filament\Resources\HomePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

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
}
