<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerPetResource\Pages;
use App\Filament\Resources\CustomerPetResource\RelationManagers;
use App\Models\CustomerPet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerPetResource extends Resource
{
    protected static ?string $model = CustomerPet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pet_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pet_breed_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('size_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                Forms\Components\TextInput::make('sex')
                    ->required(),
                Forms\Components\DatePicker::make('birthdate'),
                Forms\Components\TextInput::make('weight_kg')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('photo_path')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pet_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pet_breed_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex'),
                Tables\Columns\TextColumn::make('birthdate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight_kg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('photo_path')
                    ->searchable(),
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
            'index' => Pages\ListCustomerPets::route('/'),
            'create' => Pages\CreateCustomerPet::route('/create'),
            'edit' => Pages\EditCustomerPet::route('/{record}/edit'),
        ];
    }
}
