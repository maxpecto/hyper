<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VotingCodeResource\Pages;
use App\Models\VotingCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action as FormAction;

class VotingCodeResource extends Resource
{
    protected static ?string $model = VotingCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Oy Kodları';

    protected static ?string $modelLabel = 'Oy Kodu';

    protected static ?string $pluralModelLabel = 'Oy Kodları';

    protected static ?string $navigationGroup = 'Oylama İdarəetməsi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kod Məlumatları')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kod')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => $record !== null)
                            ->helperText('Kod avtomatik olaraq yaradılacaq'),
                        
                        Toggle::make('is_used')
                            ->label('İstifadə Edilib')
                            ->disabled()
                            ->default(false),
                        
                        DateTimePicker::make('used_at')
                            ->label('İstifadə Edildiyi Tarix')
                            ->disabled()
                            ->visible(fn ($record) => $record && $record->is_used),
                        
                        TextInput::make('used_ip')
                            ->label('İstifadə Edən IP')
                            ->disabled()
                            ->visible(fn ($record) => $record && $record->is_used),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono'),
                
                BadgeColumn::make('is_used')
                    ->label('Status')
                    ->colors([
                        'success' => false,
                        'danger' => true,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'İstifadə Edilib' : 'Aktiv'),
                
                TextColumn::make('used_at')
                    ->label('İstifadə Tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->visible(fn ($record) => $record && $record->is_used),
                
                TextColumn::make('used_ip')
                    ->label('IP Adresi')
                    ->visible(fn ($record) => $record && $record->is_used),
                
                TextColumn::make('created_at')
                    ->label('Yaradılma Tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_used')
                    ->label('Status')
                    ->placeholder('Hamısı')
                    ->trueLabel('İstifadə Edilib')
                    ->falseLabel('Aktiv'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !$record->is_used),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn ($records) => $records && $records->every(fn ($record) => !$record->is_used)),
                ]),
            ])
            ->headerActions([
                Action::make('generate_codes')
                    ->label('Kodlar Yarad')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->form([
                        TextInput::make('count')
                            ->label('Kod Sayı')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(1000)
                            ->default(100)
                            ->helperText('Bir dəfədə maksimum 1000 kod yarada bilərsiniz'),
                    ])
                    ->action(function (array $data): void {
                        $count = (int) $data['count'];
                        
                        if ($count > 1000) {
                            Notification::make()
                                ->title('Xəta')
                                ->body('Maksimum 1000 kod yarada bilərsiniz')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        VotingCode::generateCodes($count);
                        
                        Notification::make()
                            ->title('Uğurlu')
                            ->body("{$count} kod uğurla yaradıldı")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Kodlar Yarad')
                    ->modalDescription('Yeni oy kodları yaradın')
                    ->modalSubmitActionLabel('Yarad'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVotingCodes::route('/'),
            'create' => Pages\CreateVotingCode::route('/create'),
            'edit' => Pages\EditVotingCode::route('/{record}/edit'),
        ];
    }
}
