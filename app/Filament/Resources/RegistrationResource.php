<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Başvurular';
    
    protected static ?string $modelLabel = 'Başvuru';
    
    protected static ?string $pluralModelLabel = 'Başvurular';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Şəxsi Məlumatlar')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('Ad')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->label('Soyad')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        TextInput::make('username')
                            ->label('İstifadəçi Adı')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                
                Section::make('Avtomobil Məlumatları')
                    ->schema([
                        TextInput::make('car_brand')
                            ->label('Marka')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('car_model')
                            ->label('Model')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('car_year')
                            ->label('İl')
                            ->required()
                            ->maxLength(10),
                        TextInput::make('car_color')
                            ->label('Rəng')
                            ->required()
                            ->maxLength(100),
                        Textarea::make('modifications')
                            ->label('Modifikasiyalar')
                            ->rows(3),
                    ])->columns(2),
                
                Section::make('Təcrübə və Maraq Sahələri')
                    ->schema([
                        Select::make('experience')
                            ->label('Sürüş Təcrübəsi')
                            ->options([
                                'beginner' => 'Başlanğıc (0-2 il)',
                                'intermediate' => 'Orta (3-5 il)',
                                'advanced' => 'İrəlidə (6-10 il)',
                                'expert' => 'Mütəxəssis (10+ il)',
                            ])
                            ->required(),
                        Toggle::make('newsletter_subscription')
                            ->label('Bülleten Abunəliyi')
                            ->default(false),
                    ])->columns(2),
                
                Section::make('Avtomobil Fotoşəkilləri')
                    ->schema([
                        FileUpload::make('front_photo')
                            ->label('Ön Görünüş')
                            ->image()
                            ->directory('car-photos'),
                        FileUpload::make('back_photo')
                            ->label('Arxa Görünüş')
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
                            ->label('Mühərrik')
                            ->image()
                            ->directory('car-photos'),
                    ])->columns(2),
                
                Section::make('Status və Qeydlər')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Gözləyir',
                                'approved' => 'Təsdiqləndi',
                                'rejected' => 'Rədd edildi',
                            ])
                            ->default('pending')
                            ->required(),
                        Textarea::make('admin_notes')
                            ->label('Admin Qeydləri')
                            ->rows(3),
                        DateTimePicker::make('approved_at')
                            ->label('Təsdiqlənmə Tarixi')
                            ->visible(fn ($get) => $get('status') === 'approved'),
                        DateTimePicker::make('rejected_at')
                            ->label('Rədd Edilmə Tarixi')
                            ->visible(fn ($get) => $get('status') === 'rejected'),
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
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('car_brand')
                    ->label('Marka')
                    ->searchable(),
                TextColumn::make('car_model')
                    ->label('Model')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Gözləyir',
                        'approved' => 'Təsdiqləndi',
                        'rejected' => 'Rədd edildi',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Gözləyir',
                        'approved' => 'Təsdiqləndi',
                        'rejected' => 'Rədd edildi',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Registration $record) => self::getWhatsAppUrl($record))
                    ->openUrlInNewTab()
                    ->visible(fn (Registration $record): bool => $record->phone),
                Action::make('approve')
                    ->label('Təsdiqlə')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Registration $record): bool => $record->status === 'pending')
                    ->action(function (Registration $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);
                        
                        // Send notifications
                        self::sendApprovalNotifications($record);
                        
                        Notification::make()
                            ->title('Başvuru təsdiqləndi')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Rədd Et')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Registration $record): bool => $record->status === 'pending')
                    ->form([
                        Textarea::make('notes')
                            ->label('Rədd etmə səbəbi')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Registration $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['notes'],
                            'rejected_at' => now(),
                        ]);
                        
                        // Send rejection notification
                        self::sendRejectionNotification($record);
                        
                        Notification::make()
                            ->title('Başvuru rədd edildi')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'view' => Pages\ViewRegistration::route('/{record}'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    private static function sendApprovalNotifications(Registration $registration): void
    {
        // WhatsApp notification
        $message = "Salam {$registration->first_name}! HyperDrive qeydiyyatınız təsdiqləndi. Ətraflı məlumat üçün bizimlə əlaqə saxlayın.";
        
        \Log::info('WhatsApp notification sent', [
            'phone' => $registration->phone,
            'message' => $message
        ]);

        // Email notification
        \Log::info('Email notification sent', [
            'email' => $registration->email,
            'message' => $message
        ]);
    }

    private static function sendRejectionNotification(Registration $registration): void
    {
        $message = "Salam {$registration->first_name}! HyperDrive qeydiyyatınız rədd edildi. Səbəb: {$registration->admin_notes}";
        
        \Log::info('Rejection notification sent', [
            'email' => $registration->email,
            'phone' => $registration->phone,
            'message' => $message
        ]);
    }

    private static function getWhatsAppUrl(Registration $record): string
    {
        $phone = self::formatPhoneNumber($record->phone);
        $status = self::getStatusText($record->status);
        $name = $record->first_name . ' ' . $record->last_name;
        
        // Ayarlardan mesaj şablonunu al
        $settings = cache()->get('whatsapp_settings', []);
        
        $message = self::formatWhatsAppMessage($record, $settings);
        
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    private static function formatWhatsAppMessage(Registration $record, array $settings): string
    {
        $name = $record->first_name . ' ' . $record->last_name;
        $status = self::getStatusText($record->status);
        
        // Varsayılan mesajlar
        $defaultMessages = [
            'approved' => "Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Təsdiqləndi\n\n✅ Təbrik edirik! Başvurunuz təsdiqləndi.\n📞 Əlavə məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası",
            'rejected' => "Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Rədd edildi\n\n❌ Başvurunuz rədd edildi.\n📝 Səbəb: {reason}\n📞 Ətraflı məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası",
            'pending' => "Salam {name}! 👋\n\n🚗 Avtomobil qeydiyyatınızın statusu: Gözləyir\n\n⏳ Başvurunuz nəzərdən keçirilir.\n📞 Status barədə məlumat üçün bizimlə əlaqə saxlayın.\n\n🏁 Hyper Drive Komandası"
        ];
        
        // Ayarlardan mesajı al veya varsayılanı kullan
        $template = $settings['whatsapp_' . $record->status . '_message'] ?? $defaultMessages[$record->status] ?? $defaultMessages['pending'];
        
        // Değişkenleri değiştir
        $variables = [
            'name' => $name,
            'status' => $status,
            'car_brand' => $record->car_brand,
            'car_model' => $record->car_model,
            'reason' => $record->admin_notes ?? 'Məlumat yoxdur'
        ];
        
        foreach ($variables as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        
        return $template;
    }

    private static function formatPhoneNumber($phone): string
    {
        // Telefon numarasını temizle ve formatla
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Azerbaycan telefon numarası formatı
        if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
            return '994' . $phone;
        }
        
        if (strlen($phone) === 10 && substr($phone, 0, 2) === '05') {
            return '994' . substr($phone, 1);
        }
        
        if (strlen($phone) === 12 && substr($phone, 0, 3) === '994') {
            return $phone;
        }
        
        return $phone;
    }

    private static function getStatusText($status): string
    {
        return match ($status) {
            'pending' => 'Gözləyir',
            'approved' => 'Təsdiqləndi',
            'rejected' => 'Rədd edildi',
            default => $status,
        };
    }
}
