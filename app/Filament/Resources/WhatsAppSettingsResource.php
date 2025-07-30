<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppSettingsResource\Pages;
use App\Models\WhatsAppSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class WhatsAppSettingsResource extends Resource
{
    protected static ?string $model = WhatsAppSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'WhatsApp Ayarları';

    protected static ?string $modelLabel = 'WhatsApp Ayarı';

    protected static ?string $pluralModelLabel = 'WhatsApp Ayarları';

    protected static ?string $slug = 'whatsapp-settings';

    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('WhatsApp Mesaj Şablonları')
                    ->description('Başvuru durumlarına göre gönderilecek WhatsApp mesajlarını düzenleyin.')
                    ->schema([
                        Forms\Components\Toggle::make('whatsapp_enabled')
                            ->label('WhatsApp Bildirimləri Aktiv')
                            ->default(true),
                        
                        Forms\Components\Textarea::make('whatsapp_approved_message')
                            ->label('Təsdiqləndi Mesajı')
                            ->rows(8)
                            ->placeholder("Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Təsdiqləndi\n\n✅ Təbrik edirik! Başvurunuz təsdiqləndi.\n📞 Əlavə məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası")
                            ->helperText('Kullanılabilir değişkenler: {name}, {status}, {car_brand}, {car_model}')
                            ->required(),
                        
                        Forms\Components\Textarea::make('whatsapp_rejected_message')
                            ->label('Rədd Edildi Mesajı')
                            ->rows(8)
                            ->placeholder("Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Rədd edildi\n\n❌ Başvurunuz rədd edildi.\n📝 Səbəb: {reason}\n📞 Ətraflı məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası")
                            ->helperText('Kullanılabilir değişkenler: {name}, {status}, {reason}, {car_brand}, {car_model}')
                            ->required(),
                        
                        Forms\Components\Textarea::make('whatsapp_pending_message')
                            ->label('Gözləyir Mesajı')
                            ->rows(8)
                            ->placeholder("Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Gözləyir\n\n⏳ Başvurunuz nəzərdən keçirilir.\n📞 Status barədə məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası")
                            ->helperText('Kullanılabilir değişkenler: {name}, {status}, {car_brand}, {car_model}')
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('whatsapp_enabled')
                    ->label('WhatsApp Aktiv')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Bəli' : 'Xeyr'),
                Tables\Columns\TextColumn::make('whatsapp_approved_message')
                    ->label('Təsdiqləndi Mesajı')
                    ->limit(50),
                Tables\Columns\TextColumn::make('whatsapp_rejected_message')
                    ->label('Rədd Edildi Mesajı')
                    ->limit(50),
                Tables\Columns\TextColumn::make('whatsapp_pending_message')
                    ->label('Gözləyir Mesajı')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWhatsAppSettings::route('/'),
            'create' => Pages\CreateWhatsAppSettings::route('/create'),
            'edit' => Pages\EditWhatsAppSettings::route('/{record}/edit'),
        ];
    }
}
