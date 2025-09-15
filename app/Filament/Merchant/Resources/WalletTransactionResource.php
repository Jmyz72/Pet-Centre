<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\WalletTransactionResource\Pages;
use App\Models\WalletTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Colors\Color;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Transaction History';
    protected static ?string $navigationGroup = 'Financial Management';
    protected static ?string $modelLabel = 'Transaction';
    protected static ?int $navigationSort = 20;

    public static function getEloquentQuery(): Builder
    {
        // Only show the current merchant's transactions
        $merchantId = auth()->user()->merchantProfile->id ?? 0;
        return parent::getEloquentQuery()
            ->where('wallet_type', 'App\Models\MerchantWallet')
            ->whereIn('wallet_id', function ($query) use ($merchantId) {
                $query->select('id')
                    ->from('merchant_wallets')
                    ->where('merchant_id', $merchantId);
            });
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
                Tables\Columns\TextColumn::make('booking.id')
                    ->label('Booking')
                    ->prefix('#')
                    ->url(fn (WalletTransaction $record) => $record->booking 
                        ? route('filament.merchant.resources.bookings.view', $record->booking)
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
                    ->color(fn (WalletTransaction $record): string => 
                        $record->type === 'credit' ? 'success' : 'danger'
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('release_code')
                    ->label('Release Code')
                    ->badge()
                    ->color('warning')
                    ->copyable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('merchant_amount')
                    ->label('Net Amount')
                    ->prefix('RM ')
                    ->numeric(decimalPlaces: 2)
                    ->placeholder('—')
                    ->color('success'),
                Tables\Columns\TextColumn::make('released_at')
                    ->label('Released At')
                    ->dateTime()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
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
                // No actions needed - transactions are view-only records
            ])
            ->bulkActions([
                // No bulk actions - transactions cannot be modified
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletTransactions::route('/'),
            // Removed create and edit pages - transactions are generated automatically
        ];
    }
}
