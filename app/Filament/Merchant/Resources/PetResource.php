<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\PetResource\Pages;
use App\Models\Pet;
use App\Models\PetType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\{TextInput, Select, Textarea, FileUpload, Hidden};

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('merchant_id')
                ->default(fn () => Auth::id()),

            TextInput::make('name')
                ->label('Pet Name')
                ->required()
                ->maxLength(255),

            Select::make('pet_type_id')
                ->label('Pet Type')
                ->relationship('petType', 'name') // assumes you have Pet::petType() relationship
                ->searchable()
                ->required(),

            TextInput::make('breed')
                ->label('Breed')
                ->maxLength(255),

            TextInput::make('age')
                ->numeric()
                ->label('Age (Years)')
                ->minValue(0)
                ->maxValue(25),

            FileUpload::make('image')
                ->label('Pet Image')
                ->directory('pets')
                ->disk('public')
                ->image()
                ->imagePreviewHeight('200')
                ->maxSize(1024),

            Textarea::make('description')
                ->label('Description')
                ->rows(4)
                ->maxLength(1000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->label('Photo')
                ->disk('public')
                ->visibility('public')
                ->circular(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('petType.name')->label('Type'),
                Tables\Columns\TextColumn::make('breed')->label('Breed'),
                Tables\Columns\TextColumn::make('age')->label('Age'),
            ])
            ->filters([])
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
        return [];
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
