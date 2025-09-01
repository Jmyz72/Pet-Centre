<?php

namespace App\Filament\Merchant\Resources\PackageResource\RelationManagers;

use App\Models\PackageBreed;
use App\Models\PackagePetType;
use App\Models\PackageSize;
use App\Models\PetBreed;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

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
                        ->filter(fn ($label) => ! is_null($label))
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
                            $ps->id => optional($ps->size)->label,
                        ])
                        ->filter(fn ($label) => ! is_null($label))
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
                        ->filter(fn ($label) => ! is_null($label))
                        ->toArray();
                })
                ->reactive()
                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                    if (empty($state)) {
                        return;
                    }

                    // Get the chosen PackageBreed (pivot) with its PetBreed to access pet_type_id
                    $pb = \App\Models\PackageBreed::with(['breed:id,pet_type_id'])->find($state);

                    if (! $pb || ! $pb->breed || empty($pb->breed->pet_type_id)) {
                        return;
                    }

                    // Find the corresponding PackagePetType row for the same package & pet_type
                    $ppt = \App\Models\PackagePetType::where('package_id', $pb->package_id)
                        ->where('pet_type_id', $pb->breed->pet_type_id)
                        ->first();

                    if ($ppt) {
                        // Auto-select Pet Type to match the selected Breed
                        $set('package_pet_type_id', $ppt->id);
                    }
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
                Tables\Columns\TextColumn::make('petTypePivot.petType.name')->label('Pet Type')->searchable(),
                Tables\Columns\TextColumn::make('sizePivot.size.label')->label('Size')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('breedPivot.breed.name')->label('Breed')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('price')->money('MYR'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pet_type')
                    ->label('Pet Type')
                    ->options(fn () => \App\Models\PetType::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        if ($value) {
                            $query->whereHas('petTypePivot.petType', fn (Builder $q) => $q->where('id', $value));
                        }
                    }),
                Tables\Filters\SelectFilter::make('size')
                    ->label('Size')
                    ->options(fn () => \App\Models\Size::query()->orderBy('label')->pluck('label', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        if ($value) {
                            $query->whereHas('sizePivot.size', fn (Builder $q) => $q->where('id', $value));
                        }
                    }),
                Tables\Filters\SelectFilter::make('breed')
                    ->label('Breed')
                    ->options(fn () => \App\Models\PetBreed::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        if ($value) {
                            $query->whereHas('breedPivot.breed', fn (Builder $q) => $q->where('id', $value));
                        }
                    }),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('min')->label('Min Price')->numeric(),
                        Forms\Components\TextInput::make('max')->label('Max Price')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $min = $data['min'] ?? null;
                        $max = $data['max'] ?? null;
                        if ($min !== null && $min !== '') {
                            $query->where('price', '>=', $min);
                        }
                        if ($max !== null && $max !== '') {
                            $query->where('price', '<=', $max);
                        }
                    }),
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
