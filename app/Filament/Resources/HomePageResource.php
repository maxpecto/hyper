<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomePageResource\Pages;
use App\Models\HomePage;
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
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;

class HomePageResource extends Resource
{
    protected static ?string $model = HomePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Sayfa Yönetimi';

    protected static ?string $navigationLabel = 'Ana Sayfa';

    protected static ?string $modelLabel = 'Ana Sayfa';

    protected static ?string $pluralModelLabel = 'Ana Sayfalar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Hero Bölümü')
                    ->schema([
                        TextInput::make('hero_title')
                            ->label('Hero Başlığı')
                            ->required(),
                        Textarea::make('hero_subtitle')
                            ->label('Hero Alt Başlığı')
                            ->required(),
                        TextInput::make('hero_video_desktop')
                            ->label('Desktop Video URL')
                            ->url(),
                        TextInput::make('hero_video_mobile')
                            ->label('Mobile Video URL')
                            ->url(),
                        TextInput::make('hero_stats_members')
                            ->label('Üye Sayısı')
                            ->numeric()
                            ->required(),
                        TextInput::make('hero_stats_events')
                            ->label('Etkinlik Sayısı')
                            ->numeric()
                            ->required(),
                        TextInput::make('hero_stats_cars')
                            ->label('Araba Sayısı')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                Section::make('Son Etkinlik')
                    ->schema([
                        TextInput::make('latest_event_title')
                            ->label('Etkinlik Başlığı')
                            ->required(),
                        TextInput::make('latest_event_date')
                            ->label('Tarih')
                            ->required(),
                        TextInput::make('latest_event_location')
                            ->label('Konum')
                            ->required(),
                        TextInput::make('latest_event_spots')
                            ->label('Durum')
                            ->required(),
                        FileUpload::make('latest_event_image')
                            ->label('Etkinlik Görseli')
                            ->image()
                            ->directory('events')
                            ->visibility('public'),
                    ])->columns(2),

                Section::make('Hakkında Bölümü')
                    ->schema([
                        TextInput::make('about_title')
                            ->label('Başlık')
                            ->required(),
                        Textarea::make('about_description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(4),
                        Repeater::make('about_features')
                            ->label('Özellikler')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('İkon')
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Başlık')
                                    ->required(),
                                TextInput::make('description')
                                    ->label('Açıklama')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(4),
                    ]),

                Section::make('Öne Çıkan Etkinlikler')
                    ->schema([
                        TextInput::make('featured_events_title')
                            ->label('Bölüm Başlığı')
                            ->required(),
                        Repeater::make('featured_events')
                            ->label('Etkinlikler')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Başlık')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Açıklama')
                                    ->required(),
                                TextInput::make('date')
                                    ->label('Tarih')
                                    ->required(),
                                TextInput::make('location')
                                    ->label('Konum')
                                    ->required(),
                                TextInput::make('participants')
                                    ->label('Katılımcı')
                                    ->required(),
                                TextInput::make('image')
                                    ->label('Görsel URL')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(3),
                    ]),

                Section::make('Topluluk Bölümü')
                    ->schema([
                        TextInput::make('community_title')
                            ->label('Başlık')
                            ->required(),
                        Textarea::make('community_description')
                            ->label('Açıklama')
                            ->required()
                            ->rows(4),
                        Repeater::make('community_features')
                            ->label('Özellikler')
                            ->schema([
                                TextInput::make('feature')
                                    ->label('Özellik')
                                    ->required(),
                            ])
                            ->defaultItems(4),
                        TextInput::make('community_stats_members')
                            ->label('Üye Sayısı')
                            ->numeric()
                            ->required(),
                        TextInput::make('community_stats_events')
                            ->label('Etkinlik Sayısı')
                            ->numeric()
                            ->required(),
                        TextInput::make('community_stats_cars')
                            ->label('Araba Sayısı')
                            ->numeric()
                            ->required(),
                        TextInput::make('community_stats_brands')
                            ->label('Marka Sayısı')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                Section::make('Footer')
                    ->schema([
                        Textarea::make('footer_description')
                            ->label('Açıklama')
                            ->required(),
                        TextInput::make('footer_email')
                            ->label('E-posta')
                            ->email()
                            ->required(),
                        TextInput::make('footer_instagram')
                            ->label('Instagram URL')
                            ->url(),
                        TextInput::make('footer_tiktok')
                            ->label('TikTok URL')
                            ->url(),
                        TextInput::make('footer_youtube')
                            ->label('YouTube URL')
                            ->url(),
                        TextInput::make('footer_telegram')
                            ->label('Telegram URL')
                            ->url(),
                        TextInput::make('footer_location')
                            ->label('Konum')
                            ->required(),
                    ])->columns(2),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hero_title')
                    ->label('Başlık')
                    ->searchable(),
                TextColumn::make('hero_stats_members')
                    ->label('Üye Sayısı')
                    ->sortable(),
                TextColumn::make('hero_stats_events')
                    ->label('Etkinlik Sayısı')
                    ->sortable(),
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
            'index' => Pages\ListHomePages::route('/'),
            'edit' => Pages\EditHomePage::route('/{record}/edit'),
            'view' => Pages\ViewHomePage::route('/{record}'),
        ];
    }
}
