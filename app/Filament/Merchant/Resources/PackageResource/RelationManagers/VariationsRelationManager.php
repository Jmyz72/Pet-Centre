<?php

namespace App\Filament\Merchant\Resources\PackageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\PackagePetType;
use App\Models\PackageSize;
use App\Models\PackageBreed;
use Filament\Forms\Get;
use App\Models\PetType;
use App\Models\Size;
use App\Models\PetBreed;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations'; // MUST match Package::variations()
    protected static ?string $title = 'Variations';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('package_pet_type_id')
                ->label('Pet Type')
                ->options(function ($livewire) {
                    $packageId = $livewire->getOwnerRecord()->id;
                    return PackagePetType::with('petType')
                        ->where('package_id', $packageId)
                        ->get()
                        ->mapWithKeys(fn ($ppt) => [
                            $ppt->id => optional($ppt->petType)->name,
                        ])
                        ->filter(fn ($label) => !is_null($label))
                        ->toArray();
                })
                ->reactive()
                ->required(),

            Forms\Components\Select::make('package_size_id')
                ->label('Size')
                ->options(function ($livewire) {
                    $packageId = $livewire->getOwnerRecord()->id;
                    return PackageSize::with('size')
                        ->where('package_id', $packageId)
                        ->get()
                        ->mapWithKeys(fn ($ps) => [
                            $ps->id => optional($ps->size)->name,
                        ])
                        ->filter(fn ($label) => !is_null($label))
                        ->toArray();
                })
                ->nullable(),

            Forms\Components\Select::make('package_breed_id')
                ->label('Breed')
                ->options(function (Get $get, $livewire) {
                    $packageId = $livewire->getOwnerRecord()->id;
                    $pptId = $get('package_pet_type_id');

                    $query = PackageBreed::with('breed')
                        ->where('package_id', $packageId);

                    if ($pptId) {
                        $petTypeId = optional(PackagePetType::with('petType')->find($pptId))->pet_type_id;
                        if ($petTypeId) {
                            $query->whereHas('breed', fn ($q) => $q->where('pet_type_id', $petTypeId));
                        }
                    }

                    return $query->get()
                        ->mapWithKeys(fn ($pb) => [
                            $pb->id => optional($pb->breed)->name,
                        ])
                        ->filter(fn ($label) => !is_null($label))
                        ->toArray();
                })
                ->nullable(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->minValue(0)
                ->required(),

            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('petTypePivot.petType.name')->label('Pet Type'),
                Tables\Columns\TextColumn::make('sizePivot.size.name')->label('Size')->toggleable(),
                Tables\Columns\TextColumn::make('breedPivot.breed.name')->label('Breed')->toggleable(),
                Tables\Columns\TextColumn::make('price')->money('MYR'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}