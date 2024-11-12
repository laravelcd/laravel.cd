<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use App\Events\UserBannedEvent;
use Filament\Resources\Resource;
use App\Events\UserUnbannedEvent;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'untitledui-users-02';

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query): void {
                $query->whereHas('roles', function (Builder $query): void {
                    $query->whereNotIn('name', ['admin', 'moderator']);
                })
                    ->latest();
            })
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_url')
                    ->label('Avatar')
                    ->circular(),
                BadgeableColumn::make('name')
                    ->suffixBadges([
                        Badge::make('username')
                            ->label(fn ($record) => "@{$record->username}")
                            ->color('gray'),
                    ])
                    ->description(fn ($record): ?string => $record->location)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon('untitledui-inbox')
                    ->description(fn ($record): ?string => $record->phone_number),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('global.ban.label.validate_email'))
                    ->placeholder('N/A')
                    ->date(),
                Tables\Columns\TextColumn::make(name: 'created_at')
                    ->label(__('global.ban.label.inscription'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label(__('global.ban.label.email_verified'))
                    ->nullable(),
            ])
            ->actions([
                    Tables\Actions\Action::make('ban')
                        ->label(__('actions.ban'))
                        ->icon('untitledui-archive')
                        ->color('warning')
                        ->visible(fn ($record) => $record->banned_at == null)
                        ->modalHeading(__('global.ban.heading'))
                        ->modalDescription(__('global.ban.description'))
                        ->form([
                            
                    TextInput::make('banned_reason')
                                ->label(__('global.ban.reason'))
                                ->required(),
                        ])
                        ->action(function (User $record, array $data) {
                            if (!self::canBanUser($record)) {
                                Notification::make()
                                    ->warning()
                                    ->title(__('notifications.user.cannot.title'))
                                    ->body(__('notifications.user.cannot.ban_admin'))
                                    ->duration(5000)
                                    ->send();
                
                                return;
                            }
                            self::BanUserAction($record, $data['banned_reason']);
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\Action::make('unban')
                        ->label(__('actions.unban'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->banned_at !== null)
                        ->action(function (User $record) {
                            self::UnbanUserAction($record);
                        })
                        ->requiresConfirmation(),
                
                    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function BanUserAction(User $record, $reason): void
    {
        if ($record->banned_at !== null) {
            Notification::make()
                ->warning()
                ->title(__('notifications.user.cannot.title'))
                ->body(__('notifications.user.cannot.body'))
                ->send();
    
            return;
        }
        
        $record->banned_at = Carbon::now();
        $record->banned_reason = $reason;
        $record->save();

        Notification::make()
            ->success()
            ->duration(5000)
            ->title(__('notifications.user.banned.title'))
            ->body(__('notifications.user.banned.body'))
            ->send();
        
        event(new UserBannedEvent($record));
    }

    public static function UnbanUserAction(User $record): void
    {
        $record->banned_at = null;
        $record->banned_reason = null;
        $record->save();

        Notification::make()
            ->success()
            ->title(__('notifications.user.unbanned.title'))
            ->duration(5000)
            ->body(__('notifications.user.unbanned.body'))
            ->send();

        event(new UserUnbannedEvent($record));
    }

    public static function canBanUser(User $record): bool
    {
        return !$record->hasRole('admin');
    }
}