<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Araç Yönetimi';

    protected static ?string $navigationLabel = 'Arabalar';

    protected static ?string $modelLabel = 'Araba';

    protected static ?string $pluralModelLabel = 'Arabalar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Araç Bilgileri')
                    ->schema([
                        TextInput::make('title')
                            ->label('Araç Başlığı')
                            ->required(),
                        TextInput::make('brand')
                            ->label('Marka')
                            ->required(),
                        TextInput::make('model')
                            ->label('Model')
                            ->required(),
                        TextInput::make('year')
                            ->label('Yıl')
                            ->required(),
                        TextInput::make('color')
                            ->label('Renk')
                            ->required(),
                        TextInput::make('modification')
                            ->label('Modifikasyon')
                            ->required(),
                    ])->columns(2),

                Section::make('Performans Bilgileri')
                    ->schema([
                        TextInput::make('horsepower')
                            ->label('Beygir Gücü')
                            ->required(),
                        TextInput::make('acceleration')
                            ->label('0-100 Hızlanma')
                            ->required(),
                        Select::make('category')
                            ->label('Kategori')
                            ->options(Car::getCategoryOptions())
                            ->required(),
                        TagsInput::make('tags')
                            ->label('Etiketler')
                            ->suggestions(Car::getTagOptions())
                            ->required(),
                    ])->columns(2),

                Section::make('Sahip Bilgileri')
                    ->schema([
                        TextInput::make('owner_name')
                            ->label('Sahip Adı')
                            ->required(),
                        TextInput::make('owner_username')
                            ->label('Kullanıcı Adı')
                            ->required(),
                        TextInput::make('owner_avatar')
                            ->label('Profil Fotoğrafı URL')
                            ->url(),
                    ])->columns(2),

                Section::make('Görseller')
                    ->schema([
                        TextInput::make('main_image')
                            ->label('Ana Görsel URL')
                            ->url()
                            ->required(),
                        TagsInput::make('gallery_images')
                            ->label('Galeri Görselleri URL')
                            ->separator(',')
                            ->suggestions([]),
                    ])->columns(2),

                Section::make('Değerlendirme')
                    ->schema([
                        TextInput::make('rating')
                            ->label('Puan')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.1)
                            ->default(5.0),
                        TextInput::make('rating_count')
                            ->label('Değerlendirme Sayısı')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),

                Section::make('Durum')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Öne Çıkan')
                            ->default(false),
                        Toggle::make('is_manual')
                            ->label('Manuel Eklenen')
                            ->default(true)
                            ->helperText('Başvuru kaynaklı ise işaretlemeyin'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->label('Görsel')
                    ->circular()
                    ->size(50),
                TextColumn::make('title')
                    ->label('Araç')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('owner_name')
                    ->label('Sahip')
                    ->searchable(),
                TextColumn::make('brand')
                    ->label('Marka')
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bmw' => 'info',
                        'mercedes' => 'success',
                        'audi' => 'warning',
                        'japanese' => 'danger',
                        'other' => 'gray',
                    }),
                TextColumn::make('rating')
                    ->label('Puan')
                    ->sortable(),
                ToggleColumn::make('is_featured')
                    ->label('Öne Çıkan'),
                ToggleColumn::make('is_manual')
                    ->label('Manuel'),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
                TextColumn::make('created_at')
                    ->label('Eklenme Tarihi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Car::getCategoryOptions()),
                TernaryFilter::make('is_manual')
                    ->label('Kaynak')
                    ->placeholder('Tümü')
                    ->trueLabel('Manuel Eklenen')
                    ->falseLabel('Başvuru Kaynaklı'),
                TernaryFilter::make('is_featured')
                    ->label('Öne Çıkan'),
                TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
            'view' => Pages\ViewCar::route('/{record}'),
        ];
    }
}
