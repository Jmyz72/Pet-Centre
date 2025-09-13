<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformWalletResource\Pages;
use App\Models\PlatformWallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;

class PlatformWalletResource extends Resource
{
    protected static ?string $model = PlatformWallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationLabel = 'Platform Wallets';
    
    protected static ?string $modelLabel = 'Platform Wallet';
    
    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Platform Wallet Details')
                    ->schema([
                        Forms\Components\Select::make('wallet_type')
                            ->label('Wallet Type')
                            ->options([
                                'transaction_fees' => 'Transaction Fees',
                                'platform_fees' => 'Platform Fees',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('balance')
                            ->label('Balance')
                            ->prefix('RM')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('currency')
                            ->label('Currency')
                            ->default('MYR')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wallet_type')
                    ->label('Type')
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
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->prefix('RM ')
                    ->numeric(decimalPlaces: 2)
                    ->weight(FontWeight::Bold)
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('transactions_count')
                    ->label('Transactions')
                    ->counts('transactions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('wallet_type')
                    ->options([
                        'transaction_fees' => 'Transaction Fees',
                        'platform_fees' => 'Platform Fees',
                    ])
                    ->label('Wallet Type'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for platform wallets
            ])
            ->defaultSort('wallet_type', 'asc');
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
            'index' => Pages\ListPlatformWallets::route('/'),
            'create' => Pages\CreatePlatformWallet::route('/create'),
            'edit' => Pages\EditPlatformWallet::route('/{record}/edit'),
        ];
    }
}