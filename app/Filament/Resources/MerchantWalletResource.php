<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerchantWalletResource\Pages;
use App\Filament\Resources\MerchantWalletResource\RelationManagers;
use App\Models\MerchantWallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MerchantWalletResource extends Resource
{
    protected static ?string $model = MerchantWallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('merchant_id')
                    ->relationship('merchant', 'name')
                    ->required(),
                Forms\Components\TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('pending_balance')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('MYR'),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('merchant.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pending_balance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMerchantWallets::route('/'),
            'create' => Pages\CreateMerchantWallet::route('/create'),
            'edit' => Pages\EditMerchantWallet::route('/{record}/edit'),
        ];
    }
}
