<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\PackageResource\Pages;
use App\Models\Package;
use App\Filament\Traits\MerchantScopedResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchantProfile;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\PetBreed;

class PackageResource extends Resource
{
    use MerchantScopedResource;

    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('merchant_id')
                ->default(function () {
                    $userId = Auth::id();
                    if (!$userId) return null;
                    $profileId = optional(Auth::user()->merchantProfile)->id
                        ?? MerchantProfile::where('user_id', $userId)->value('id');
                    return $profileId;
                })
                ->required(),

            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Package Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('price')
                        ->label('Price (MYR)')
                        ->numeric()
                        ->step('0.01')
                        ->minValue(0)
                        ->suffix('MYR')
                        ->required(),
                ]),

            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('packageTypes')
                        ->label('Types')
                        ->relationship('packageTypes', 'name')
                        ->multiple()
                        ->required()
                        ->preload()
                        ->optionsLimit(1000)
                        ->searchable(),

                    Forms\Components\Select::make('packageSizes')
                        ->label('Sizes')
                        ->relationship('packageSizes', 'label')
                        ->multiple()
                        ->required()
                        ->preload()
                        ->searchable(),
                ]),

            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('petTypes')
                        ->label('Pet Types')
                        ->relationship('petTypes', 'name')
                        ->multiple()
                        ->required()
                        ->preload()
                        ->searchable()
                        ->optionsLimit(1000)
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $currentBreeds = $get('petBreeds') ?? [];
                            if (! empty($currentBreeds) && ! empty($state)) {
                                $validBreedIds = PetBreed::whereIn('id', $currentBreeds)
                                    ->whereIn('pet_type_id', $state)
                                    ->pluck('id')
                                    ->toArray();
                                $set('petBreeds', $validBreedIds);
                            } else {
                                $set('petBreeds', []);
                            }
                        }),

                    Forms\Components\Select::make('petBreeds')
                        ->label('Breeds')
                        ->relationship(
                            'petBreeds',
                            'name',
                            function (Builder $query, Get $get) {
                                $typeIds = $get('petTypes') ?? [];
                                if (! empty($typeIds)) {
                                    $query->whereIn('pet_type_id', $typeIds);
                                } else {
                                    // Return no options until a pet type is selected
                                    $query->whereRaw('1 = 0');
                                }
                            }
                        )
                        ->getOptionLabelFromRecordUsing(function (PetBreed $record) {
                            $type = optional($record->petType)->name ?? 'Unknown';
                            return "{$record->name}  —  [{$type}]";
                        })
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->optionsLimit(1000)
                        ->reactive()
                        ->disabled(fn (Get $get) => empty($get('petTypes')))
                        ->hint(fn (Get $get) => empty($get('petTypes')) ? 'Select Pet Types first' : null)
                        ->rules(function (Get $get) {
                            return [
                                function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $ids = is_array($value) ? $value : (array) $value;
                                    $typeIds = $get('petTypes') ?? [];
                                    if (empty($ids) || empty($typeIds)) {
                                        return;
                                    }
                                    $valid = PetBreed::whereIn('id', $ids)
                                        ->whereIn('pet_type_id', $typeIds)
                                        ->count();
                                    if ($valid !== count($ids)) {
                                        $fail('One or more selected breeds do not belong to the selected pet types.');
                                    }
                                }
                            ];
                        }),
                ]),

            Forms\Components\TextInput::make('duration_minutes')
                ->label('Duration (minutes)')
                ->numeric()
                ->minValue(0)
                ->placeholder('e.g. 30')
                ->suffix('min')
                ->datalist([10, 15, 30, 60, 90, 120])
                ->helperText('Pick a common duration or type any number of minutes')
                ->nullable(),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(3)
                ->nullable(),

            // Active toggle at the end
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TagsColumn::make('packageTypes.name')
                    ->label('Types')
                    ->limit(3),

                Tables\Columns\TagsColumn::make('packageSizes.label')
                    ->label('Sizes')
                    ->limit(4),

                Tables\Columns\TagsColumn::make('petTypes.name')
                    ->label('Pet Types')
                    ->limit(3),

                Tables\Columns\TagsColumn::make('petBreeds.name')
                    ->label('Breeds')
                    ->limit(4)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function ($record) {
                        $names = $record->petBreeds->pluck('name')->all();
                        return empty($names) ? null : implode(', ', $names);
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('MYR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration (min)')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('packageTypes')
                    ->label('Type')
                    ->relationship('packageTypes', 'name')
                    ->multiple(),

                SelectFilter::make('packageSizes')
                    ->label('Size')
                    ->relationship('packageSizes', 'label')
                    ->multiple(),

                SelectFilter::make('petTypes')
                    ->label('Pet Type')
                    ->relationship('petTypes', 'name')
                    ->multiple(),

                SelectFilter::make('petBreeds')
                    ->label('Breed')
                    ->relationship('petBreeds', 'name')
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // no relation managers yet
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit'   => Pages\EditPackage::route('/{record}/edit'),
        ];
    }

    /**
     * Extra safety: enforce merchant_id server-side on create/update
     * (hidden fields can be tampered with; trait sets on create, this covers edits too).
     */
    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data = static::mutateFormDataBeforeCreate($data);

        return $data;
    }
}
