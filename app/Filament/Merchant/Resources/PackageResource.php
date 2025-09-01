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

            Forms\Components\Select::make('packageTypes')
                ->label('Types')
                ->relationship('packageTypes', 'name')
                ->multiple()
                ->required()
                ->preload()
                ->searchable()
                ->columnSpanFull(),

            Forms\Components\Select::make('packageSizes')
                ->label('Sizes')
                ->relationship('packageSizes', 'label')
                ->multiple()
                ->required()
                ->preload()
                ->searchable()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('name')
                ->label('Package Name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(3)
                ->nullable(),

            Forms\Components\TextInput::make('price')
                ->label('Price (MYR)')
                ->numeric()
                ->step('0.01')
                ->minValue(0)
                ->required(),

            Forms\Components\TextInput::make('duration_minutes')
                ->label('Duration (minutes)')
                ->numeric()
                ->minValue(0)
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
                    ->label('Types'),

                Tables\Columns\TagsColumn::make('packageSizes.label')
                    ->label('Sizes'),

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
