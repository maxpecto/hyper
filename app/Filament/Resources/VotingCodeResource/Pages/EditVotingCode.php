<?php

namespace App\Filament\Resources\VotingCodeResource\Pages;

use App\Filament\Resources\VotingCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVotingCode extends EditRecord
{
    protected static string $resource = VotingCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
