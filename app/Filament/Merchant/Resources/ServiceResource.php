<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchantProfile;

class ServiceResource extends Resource
{

    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Services';
    protected static ?string $navigationGroup = 'Clinic Management';
    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $merchantProfile = $user?->merchantProfile;
        
        // Only show for clinic merchants
        return $merchantProfile && $merchantProfile->role === 'clinic';
    }

    public static function getEloquentQuery(): Builder
    {
        // Only show services that belong to the current merchant
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
                ->label('Service Name')
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. Rabies Vaccination'),

            Select::make('service_type_id')
                ->label('Service Type')
                ->relationship('serviceType', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Textarea::make('description')
                ->label('Description')
                ->rows(4)
                ->nullable()
                ->maxLength(1000)
                ->columnSpanFull(),

            TextInput::make('price')
                ->label('Price (RM)')
                ->numeric()
                ->minValue(0)
                ->step('0.01')
                ->required()
                ->placeholder('0.00'),

            TextInput::make('duration_minutes')
                ->label('Duration (minutes)')
                ->numeric()
                ->minValue(5)
                ->maxValue(600)
                ->required()
                ->placeholder('30'),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
                TextColumn::make('name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('serviceType.name')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price')
                    ->label('Price (RM)')
                    ->money('MYR', locale: 'ms_MY')
                    ->sortable(),

                TextColumn::make('duration_minutes')
                    ->label('Duration (min)')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('service_type_id')
                    ->label('Type')
                    ->relationship('serviceType', 'name'),

                TernaryFilter::make('is_active')
                    ->label('Active'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }


    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data = static::mutateFormDataBeforeCreate($data);

        return $data;
    }
}
