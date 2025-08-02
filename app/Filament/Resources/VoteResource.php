<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoteResource\Pages;
use App\Models\Vote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class VoteResource extends Resource
{
    protected static ?string $model = Vote::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Oylar';

    protected static ?string $modelLabel = 'Oy';

    protected static ?string $pluralModelLabel = 'Oylar';

    protected static ?string $navigationGroup = 'Oylama İdarəetməsi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Oy Məlumatları')
                    ->schema([
                        Select::make('voting_code_id')
                            ->label('Oy Kodu')
                            ->relationship('votingCode', 'code')
                            ->required()
                            ->searchable(),
                        
                        Select::make('registration_id')
                            ->label('Avtomobil')
                            ->relationship('registration', 'car_brand')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->car_brand . ' ' . $record->car_model . ' - ' . $record->username)
                            ->required()
                            ->searchable(),
                        
                        TextInput::make('ip_address')
                            ->label('IP Adresi')
                            ->required()
                            ->maxLength(45),
                        
                        Textarea::make('user_agent')
                            ->label('Browser Məlumatı')
                            ->rows(3),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('votingCode.code')
                    ->label('Oy Kodu')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->visible(fn ($record) => $record && $record->votingCode),
                
                TextColumn::make('registration.car_brand')
                    ->label('Avtomobil Markası')
                    ->searchable()
                    ->sortable()
                    ->visible(fn ($record) => $record && $record->registration),
                
                TextColumn::make('registration.car_model')
                    ->label('Avtomobil Modeli')
                    ->searchable()
                    ->sortable()
                    ->visible(fn ($record) => $record && $record->registration),
                
                TextColumn::make('registration.username')
                    ->label('Sahib')
                    ->searchable()
                    ->sortable()
                    ->visible(fn ($record) => $record && $record->registration),
                
                TextColumn::make('ip_address')
                    ->label('IP Adresi')
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Oy Tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('registration_id')
                    ->label('Avtomobil')
                    ->relationship('registration', 'car_brand')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn ($records) => $records && $records->count() > 0),
                ]),
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
            'index' => Pages\ListVotes::route('/'),
            'create' => Pages\CreateVote::route('/create'),
            'edit' => Pages\EditVote::route('/{record}/edit'),
        ];
    }
}
