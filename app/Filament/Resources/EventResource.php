<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Sayfa Yönetimi';

    protected static ?string $navigationLabel = 'Etkinlikler';

    protected static ?string $modelLabel = 'Etkinlikler';

    protected static ?string $pluralModelLabel = 'Etkinlikler';

    public static function getNavigationUrl(): string
    {
        // Tek etkinlikler sayfası kaydını al
        $record = Event::getSettings();
        
        // Direkt edit sayfasına yönlendir
        return static::getUrl('edit', ['record' => $record->id]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sayfa Başlığı')
                    ->schema([
                        TextInput::make('page_title')
                            ->label('Sayfa Başlığı')
                            ->required(),
                        Textarea::make('page_subtitle')
                            ->label('Sayfa Alt Başlığı')
                            ->required(),
                    ])->columns(2),

                Section::make('Filtre Butonları')
                    ->schema([
                        TextInput::make('filter_all_text')
                            ->label('Tümü Butonu')
                            ->required(),
                        TextInput::make('filter_racing_text')
                            ->label('Yarış Butonu')
                            ->required(),
                        TextInput::make('filter_meet_text')
                            ->label('Görüş Butonu')
                            ->required(),
                        TextInput::make('filter_drift_text')
                            ->label('Drift Butonu')
                            ->required(),
                    ])->columns(2),

                Section::make('Etkinlikler')
                    ->schema([
                        Repeater::make('events')
                            ->label('Etkinlikler')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Etkinlik Başlığı')
                                    ->required(),
                                Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'meet' => 'Görüş',
                                        'racing' => 'Yarış',
                                        'workshop' => 'Emalatxana',
                                        'drift' => 'Drift',
                                    ])
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Açıklama')
                                    ->required()
                                    ->rows(3),
                                TextInput::make('date')
                                    ->label('Tarih')
                                    ->required(),
                                TextInput::make('location')
                                    ->label('Konum')
                                    ->required(),
                                TextInput::make('time')
                                    ->label('Saat')
                                    ->required(),
                                TextInput::make('participants')
                                    ->label('Katılımcı Sayısı')
                                    ->required(),
                                TextInput::make('price')
                                    ->label('Fiyat')
                                    ->required(),
                                TextInput::make('image')
                                    ->label('Görsel URL')
                                    ->url()
                                    ->required(),
                                Toggle::make('is_featured')
                                    ->label('Öne Çıkan')
                                    ->default(false),
                                TextInput::make('registration_url')
                                    ->label('Kayıt URL')
                                    ->default('/register'),
                                TextInput::make('details_url')
                                    ->label('Detay URL')
                                    ->default('#'),
                            ])
                            ->columns(2)
                            ->defaultItems(4)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_title')
                    ->label('Sayfa Başlığı')
                    ->searchable(),
                TextColumn::make('events')
                    ->label('Etkinlik Sayısı')
                    ->formatStateUsing(fn ($state) => count($state)),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
                TextColumn::make('updated_at')
                    ->label('Son Güncelleme')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Bulk actions kaldırıldı çünkü sadece tek kayıt olacak
            ])
            ->paginated(false); // Sayfalama kaldırıldı
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
            'index' => Pages\ListEvents::route('/'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }
}
