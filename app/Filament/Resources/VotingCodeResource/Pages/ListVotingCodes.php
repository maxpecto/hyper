<?php

namespace App\Filament\Resources\VotingCodeResource\Pages;

use App\Filament\Resources\VotingCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVotingCodes extends ListRecords
{
    protected static string $resource = VotingCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
