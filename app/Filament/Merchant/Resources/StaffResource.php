<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StaffResource\Pages;
use App\Filament\Merchant\Resources\StaffResource\RelationManagers;
use App\Models\Staff;
use Filament\Forms\Form;
use Filament\Forms\Components\{Hidden, TextInput, Select};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Domain\Staff\StaffFactory;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('merchant_id')
                ->default(fn () => auth()->user()?->merchantProfile?->id)
                ->required(),

            // Role is NOT selectable by user; it is determined from the logged-in merchant.
            Hidden::make('role')
                ->default(fn () => static::resolveMerchantRole())
                ->dehydrated(true),

            TextInput::make('name')
                ->required()
                ->maxLength(100),

            TextInput::make('email')
                ->email()
                ->maxLength(255),

            TextInput::make('phone')
                ->maxLength(30),

            Select::make('status')
                ->options([
                    'active'   => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->default('active')
                ->required(),
        ])->columns(2);
    }
    /**
     * Resolve the merchant's staff role (groomer/clinic) from the current user.
     * Falls back to 'groomer' if it cannot be determined.
     */
    protected static function resolveMerchantRole(): string
    {
        $user = auth()->user();
        $role = null;

        // If using Spatie roles, infer from role names.
        if ($user && method_exists($user, 'hasRole')) {
            if ($user->hasRole('groomer') || $user->hasRole('groomer_merchant')) {
                $role = 'groomer';
            } elseif ($user->hasRole('clinic') || $user->hasRole('clinic_merchant')) {
                $role = 'clinic';
            }
        }

        // Otherwise try merchant profile attributes like role/type/category.
        $profile = $user?->merchantProfile ?? null;
        if (!$role && $profile) {
            $candidate = $profile->role ?? $profile->type ?? $profile->category ?? null;
            if (in_array($candidate, ['groomer', 'clinic'], true)) {
                $role = $candidate;
            }
        }

        return $role ?? 'groomer';
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['merchant_id'] = $data['merchant_id'] ?? (auth()->user()?->merchantProfile?->id);
        $data['role'] = static::resolveMerchantRole();

        $behavior = StaffFactory::make($data['role']);
        return $behavior->prepare($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Always enforce role from the logged-in merchant context
        $data['role'] = static::resolveMerchantRole();
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('merchant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
