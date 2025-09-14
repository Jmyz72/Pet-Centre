<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\PetResource\Pages;
use App\Models\MerchantProfile;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\{TextInput, Select, Textarea, FileUpload, Hidden, DatePicker, DateTimePicker, Toggle, Placeholder, Grid};
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\PetBreed;
use Carbon\Carbon;
use Filament\Tables\Columns\IconColumn;
use App\Models\Size;

class PetResource extends Resource
{

    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'My Pets';
    protected static ?string $navigationGroup = 'Pet Management';
    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $merchantProfile = $user?->merchantProfile;
        
        // Only show for shelter merchants
        return $merchantProfile && $merchantProfile->role === 'shelter';
    }

    public static function getEloquentQuery(): Builder
    {
        // Only show pets that belong to the current merchant
        return parent::getEloquentQuery()
            ->where('merchant_id', auth()->user()->merchantProfile->id ?? 0);
    }

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

            TextInput::make('name')
                ->label('Pet Name')
                ->required()
                ->maxLength(255),

            DatePicker::make('date_of_birth')
                ->label('Date of Birth')
                ->closeOnDateSelection()
                ->maxDate(now())
                ->reactive()
                ->required(),

            TextInput::make('weight_kg')
                ->label('Weight (kg)')
                ->numeric()
                ->step('0.01')
                ->minValue(0)
                ->maxValue(200)
                ->suffix('kg')
                ->reactive()
                ->required(),

            Select::make('sex')
                ->label('Sex')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'unknown' => 'Unknown',
                ])
                ->default('unknown')
                ->required(),

            Select::make('pet_type_id')
                ->label('Pet Type')
                ->relationship('petType', 'name')
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    // If a breed is already selected but does not belong to the new type, clear it
                    $breedId = $get('pet_breed_id');
                    if ($breedId) {
                        $belongs = \App\Models\PetBreed::where('id', $breedId)
                            ->where('pet_type_id', $state)
                            ->exists();
                        if (! $belongs) {
                            $set('pet_breed_id', null);
                        }
                    }
                })
                ->required(),

            Select::make('pet_breed_id')
                ->label('Breed')
                ->options(fn (Get $get) => $get('pet_type_id')
                    ? PetBreed::where('pet_type_id', $get('pet_type_id'))->orderBy('name')->pluck('name', 'id')
                    : PetBreed::orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->reactive()
                ->required()
                ->rule(function (Get $get) {
                    $typeId = $get('pet_type_id');
                    return function (string $attribute, $value, \Closure $fail) use ($typeId) {
                        if (!$value) return;
                        $ok = \App\Models\PetBreed::where('id', $value)
                            ->when($typeId, fn($q) => $q->where('pet_type_id', $typeId))
                            ->exists();
                        if (! $ok) {
                            $fail('Selected breed does not belong to the selected pet type.');
                        }
                    };
                })
                ->helperText('Filtered by selected Pet Type, if any.'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'draft' => 'Draft',
                    'available' => 'Available',
                    'reserved' => 'Reserved',
                    'adopted' => 'Adopted',
                    'inactive' => 'Inactive',
                ])
                ->default('draft')
                ->required(),

            TextInput::make('adoption_fee')
                ->label('Adoption Fee (MYR)')
                ->numeric()
                ->step('0.01')
                ->minValue(0)
                ->suffix('MYR')
                ->required(),

            DateTimePicker::make('adopted_at')
                ->label('Adopted At')
                ->seconds(false)
                ->visible(fn (Get $get) => $get('status') === 'adopted')
                ->nullable(),

            FileUpload::make('image')
                ->label('Photo')
                ->directory('pets')
                ->disk('public')
                ->image()
                ->imagePreviewHeight('200')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg','image/png','image/webp'])
                ->required(),

            Textarea::make('description')
                ->label('Description')
                ->rows(4)
                ->maxLength(1000),

            Grid::make(3)->schema([
                Placeholder::make('computed_age')
                    ->label('Age (auto)')
                    ->content(function (Get $get): string {
                        $dob = $get('date_of_birth');
                        if (empty($dob)) {
                            return '—';
                        }
                        $dob = Carbon::parse($dob);
                        $now = Carbon::now();
                        $totalMonths = $dob->diffInMonths($now); // integer months difference
                        $years = intdiv($totalMonths, 12);
                        $months = $totalMonths % 12;
                        return $years > 0
                            ? "{$years} yr " . ($months > 0 ? "{$months} mo" : "")
                            : "{$months} mo";
                    }),
                Placeholder::make('computed_size')
                    ->label('Size (auto)')
                    ->content(function (Get $get): string {
                        $weight = $get('weight_kg');
                        if (empty($weight)) {
                            return '—';
                        }
                        $size = \App\Models\Size::where(function($q) use ($weight) {
                                $q->whereNull('min_weight')->orWhere('min_weight', '<=', $weight);
                            })
                            ->where(function($q) use ($weight) {
                                $q->whereNull('max_weight')->orWhere('max_weight', '>=', $weight);
                            })
                            ->first();
                        return $size?->label ?? '—';
                    }),
                Toggle::make('vaccinated')
                    ->label('Vaccinated')
                    ->default(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s') // auto refresh every 3 seconds
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('petType.name')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('petBreed.name')
                    ->label('Breed')
                    ->sortable(),
                Tables\Columns\TextColumn::make('size.label')
                    ->label('Size')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sex')
                    ->label('Sex')
                    ->sortable()
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Age')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '—';
                        $dob = Carbon::parse($state);
                        $now = Carbon::now();
                        $totalMonths = $dob->diffInMonths($now);
                        $years = intdiv($totalMonths, 12);
                        $months = $totalMonths % 12;
                        return $years > 0
                            ? "{$years} yr " . ($months > 0 ? "{$months} mo" : "")
                            : "{$months} mo";
                    }),
                IconColumn::make('vaccinated')
                    ->label('Vaccinated')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'available' => 'success',
                        'reserved'  => 'warning',
                        'adopted'   => 'primary',
                        'inactive'  => 'danger',
                        'draft'     => 'gray',
                        default     => 'secondary',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('adoption_fee')
                    ->label('Fee')
                    ->money('MYR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adopted_at')
                    ->label('Adopted At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('pet_type_id')
                    ->label('Type')
                    ->relationship('petType', 'name'),
                SelectFilter::make('pet_breed_id')
                    ->label('Breed')
                    ->relationship('petBreed', 'name'),
                SelectFilter::make('size_id')
                    ->label('Size')
                    ->relationship('size', 'label'),
                SelectFilter::make('sex')
                    ->label('Sex')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'unknown' => 'Unknown',
                    ]),
                SelectFilter::make('vaccinated')
                    ->label('Vaccinated')
                    ->options([1 => 'Yes', 0 => 'No']),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'available' => 'Available',
                        'reserved' => 'Reserved',
                        'adopted' => 'Adopted',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Preserve merchant scoping
        $data = static::mutateFormDataBeforeCreate($data);

        // Validate breed belongs to selected type; if not, null it to prevent mismatch
        if (!empty($data['pet_breed_id']) && !empty($data['pet_type_id'])) {
            $valid = \App\Models\PetBreed::where('id', $data['pet_breed_id'])
                ->where('pet_type_id', $data['pet_type_id'])
                ->exists();
            if (! $valid) {
                $data['pet_breed_id'] = null;
            }
        }
        return $data;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit'   => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}
