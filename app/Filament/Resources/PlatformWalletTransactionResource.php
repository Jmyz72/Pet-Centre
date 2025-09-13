<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformWalletTransactionResource\Pages;
use App\Models\WalletTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class PlatformWalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Platform Transactions';
    
    protected static ?string $modelLabel = 'Platform Transaction';
    
    protected static ?string $navigationGroup = 'Finance';

    public static function getEloquentQuery(): Builder
    {
        // Only show platform wallet transactions
        return parent::getEloquentQuery()
            ->where('wallet_type', 'App\Models\PlatformWallet')
            ->with(['booking']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->copyable()
                    ->searchable()
                    ->limit(8),
                Tables\Columns\TextColumn::make('wallet.wallet_type')
                    ->label('Wallet Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transaction_fees' => 'info',
                        'platform_fees' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'transaction_fees' => 'Transaction Fees',
                        'platform_fees' => 'Platform Fees',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('booking.id')
                    ->label('Booking')
                    ->prefix('#')
                    ->url(fn (WalletTransaction $record) => $record->booking 
                        ? route('filament.admin.resources.bookings.view', $record->booking)
                        : null)
                    ->color('primary'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'credit' => 'success',
                        'debit' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->prefix('RM ')
                    ->numeric(decimalPlaces: 2)
                    ->weight(FontWeight::Bold)
                    ->color('success'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(30)
                    ->tooltip(function (WalletTransaction $record): ?string {
                        return $record->description;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('wallet.wallet_type')
                    ->relationship('wallet', 'wallet_type')
                    ->options([
                        'transaction_fees' => 'Transaction Fees',
                        'platform_fees' => 'Platform Fees',
                    ])
                    ->label('Wallet Type'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'credit' => 'Credit',
                        'debit' => 'Debit',
                    ]),
            ])
            ->actions([
                // No actions needed - all info shown in table
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformWalletTransactions::route('/'),
        ];
    }
}