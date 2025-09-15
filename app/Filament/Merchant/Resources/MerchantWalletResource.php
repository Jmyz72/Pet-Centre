<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\MerchantWalletResource\Pages;
use App\Models\MerchantWallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class MerchantWalletResource extends Resource
{
    protected static ?string $model = MerchantWallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationLabel = 'My Wallet';
    protected static ?string $navigationGroup = 'Financial Management';
    protected static ?string $modelLabel = 'Wallet';
    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        // Only show the current merchant's wallet
        return parent::getEloquentQuery()
            ->where('merchant_id', auth()->user()->merchantProfile->id ?? 0);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wallet Balance')
                    ->schema([
                        Forms\Components\TextInput::make('balance')
                            ->label('Available Balance')
                            ->prefix('RM')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('pending_balance')
                            ->label('Pending Balance')
                            ->prefix('RM')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('currency')
                            ->label('Currency')
                            ->disabled(),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('balance')
                    ->label('Available Balance')
                    ->prefix('RM ')
                    ->numeric(decimalPlaces: 2)
                    ->weight(FontWeight::Bold)
                    ->color('success'),
                Tables\Columns\TextColumn::make('pending_balance')
                    ->label('Pending Balance')
                    ->prefix('RM ')
                    ->numeric(decimalPlaces: 2)
                    ->weight(FontWeight::Bold)
                    ->color('warning'),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // No actions needed - wallets are view-only for merchants
            ])
            ->bulkActions([
                // No bulk actions - wallets cannot be modified by merchants
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Wallet Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('balance')
                            ->label('Available Balance')
                            ->prefix('RM ')
                            ->color('success')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('pending_balance')
                            ->label('Pending Balance')
                            ->prefix('RM ')
                            ->color('warning')
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('currency')
                            ->label('Currency'),
                    ])->columns(3),
                    
                Infolists\Components\Section::make('Pending Release Codes')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('transactions')
                            ->relationship('transactions', fn (Builder $query) => 
                                $query->where('status', 'pending')
                                      ->whereNotNull('release_code')
                                      ->with('booking')
                                      ->latest()
                            )
                            ->schema([
                                Infolists\Components\TextEntry::make('release_code')
                                    ->label('Release Code')
                                    ->badge()
                                    ->color('warning'),
                                Infolists\Components\TextEntry::make('amount')
                                    ->label('Amount')
                                    ->prefix('RM ')
                                    ->weight(FontWeight::Bold),
                                Infolists\Components\TextEntry::make('booking.id')
                                    ->label('Booking #')
                                    ->prefix('#'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Date')
                                    ->dateTime(),
                            ])
                            ->columns(4)
                    ])
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
            'index' => Pages\ListMerchantWallets::route('/'),
            // Removed create and edit pages - wallets are managed automatically
        ];
    }
}
