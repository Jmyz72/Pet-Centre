<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Filament\Resources\PetResource\RelationManagers;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Pet Management';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(120),

                Forms\Components\Select::make('pet_type_id')
                    ->label('Pet Type')
                    ->relationship('petType', 'name') 
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('pet_breed_id')
                    ->label('Pet Breed')
                    ->relationship('petBreed', 'name') 
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('sex')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('age_months')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\FileUpload::make('photo_path')
                    ->image()
                    ->directory('pets'),

                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'pending'   => 'Pending',
                        'adopted'   => 'Adopted',
                    ])
                    ->default('available')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->rows(4)
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('petType.name')->label('Type')->sortable(),
                Tables\Columns\TextColumn::make('petBreed.name')->label('Breed')->sortable(),
                Tables\Columns\TextColumn::make('sex'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\ImageColumn::make('photo_path')->label('Photo'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}
