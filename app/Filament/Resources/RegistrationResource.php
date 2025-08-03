<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Başvurular';

    protected static ?string $navigationLabel = 'Başvurular';

    protected static ?string $modelLabel = 'Başvuru';

    protected static ?string $pluralModelLabel = 'Başvurular';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kişisel Bilgiler')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('Ad')
                            ->required(),
                        TextInput::make('last_name')
                            ->label('Soyad')
                            ->required(),
                        TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->required(),
                        TextInput::make('username')
                            ->label('Kullanıcı Adı')
                            ->required(),
                    ])->columns(2),

                Section::make('Araç Bilgileri')
                    ->schema([
                        TextInput::make('car_brand')
                            ->label('Araç Markası')
                            ->required(),
                        TextInput::make('car_model')
                            ->label('Araç Modeli')
                            ->required(),
                        TextInput::make('car_year')
                            ->label('Üretim Yılı')
                            ->required(),
                        TextInput::make('car_color')
                            ->label('Renk')
                            ->required(),
                        Textarea::make('modifications')
                            ->label('Modifikasyonlar')
                            ->rows(3),
                    ])->columns(2),

                Section::make('Deneyim ve İlgi Alanları')
                    ->schema([
                        Select::make('experience')
                            ->label('Sürüş Deneyimi')
                            ->options([
                                'beginner' => 'Başlangıç (0-2 yıl)',
                                'intermediate' => 'Orta (3-5 yıl)',
                                'advanced' => 'İleri (6-10 yıl)',
                                'expert' => 'Uzman (10+ yıl)',
                            ])
                            ->required(),
                        Repeater::make('interests')
                            ->label('İlgi Alanları')
                            ->schema([
                                TextInput::make('interest')
                                    ->label('İlgi Alanı')
                                    ->required(),
                            ])
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ])->columns(2),

                Section::make('Araç Fotoğrafları')
                    ->schema([
                        FileUpload::make('front_photo')
                            ->label('Ön Görünüş')
                            ->image()
                            ->directory('car-photos'),
                        FileUpload::make('back_photo')
                            ->label('Arka Görünüş')
                            ->image()
                            ->directory('car-photos'),
                        FileUpload::make('left_photo')
                            ->label('Sol Yan')
                            ->image()
                            ->directory('car-photos'),
                        FileUpload::make('right_photo')
                            ->label('Sağ Yan')
                            ->image()
                            ->directory('car-photos'),
                        FileUpload::make('interior_photo')
                            ->label('İç Görünüş')
                            ->image()
                            ->directory('car-photos'),
                        FileUpload::make('engine_photo')
                            ->label('Motor')
                            ->image()
                            ->directory('car-photos'),
                    ])->columns(2),

                Section::make('Durum ve İletişim')
                    ->schema([
                        Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Beklemede',
                                'approved' => 'Onaylandı',
                                'rejected' => 'Reddedildi',
                            ])
                            ->default('pending'),
                        Textarea::make('admin_notes')
                            ->label('Admin Notları')
                            ->rows(3),
                        Toggle::make('newsletter_subscription')
                            ->label('Bülten Aboneliği')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                TextColumn::make('car_full_name')
                    ->label('Araç')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Başvuru Tarihi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Registration $record): bool => $record->status === 'pending')
                    ->action(function (Registration $record) {
                        $record->approve();
                        Notification::make()
                            ->title('Başvuru onaylandı')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Registration $record): bool => $record->status === 'pending')
                    ->action(function (Registration $record) {
                        $record->reject();
                        Notification::make()
                            ->title('Başvuru reddedildi')
                            ->danger()
                            ->send();
                    }),
                Action::make('add_to_cars')
                    ->label('Arabalara Ekle')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Registration $record): bool => $record->status === 'approved')
                    ->action(function (Registration $record) {
                        // Araba oluştur
                        $car = Car::createFromRegistration($record);
                        
                        Notification::make()
                            ->title('Araç arabalar sayfasına eklendi')
                            ->success()
                            ->send();
                        
                        // Arabalar sayfasına yönlendir
                        return redirect()->route('filament.admin.resources.cars.edit', ['record' => $car->id]);
                    }),
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
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
            'view' => Pages\ViewRegistration::route('/{record}'),
        ];
    }
}
