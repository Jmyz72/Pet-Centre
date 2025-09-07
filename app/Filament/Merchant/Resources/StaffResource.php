<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StaffResource\Pages;
use App\Filament\Merchant\Resources\StaffResource\RelationManagers;
use App\Models\Staff;
use App\Models\Package;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Forms\Components\{Hidden, TextInput, Select};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Domain\Staff\StaffFactory;
use Illuminate\Validation\ValidationException;
use App\Filament\Traits\MerchantScopedResource;

class StaffResource extends Resource
{
    use MerchantScopedResource;

    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('merchant_id')
                ->default(fn () => auth()->user()?->merchantProfile?->id)
                ->required(),

            // Ensure 'role' is included in the payload (user cannot change it)
            Hidden::make('role')
                ->dehydrated(true)
                ->default(fn () => static::resolveMerchantRoleStrict()),

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

            // Packages (shown only for GROOMER merchants)
            Select::make('packages')
                ->label('Packages')
                ->relationship('packages', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->options(fn () =>
                    Package::query()
                        ->where('merchant_id', auth()->user()?->merchantProfile?->id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->visible(fn () => static::resolveMerchantRoleStrict() === 'groomer')
                ->dehydrated(true),

            // Services (shown only for CLINIC merchants)
            Select::make('services')
                ->label('Services')
                ->relationship('services', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->options(fn () =>
                    Service::query()
                        ->where('merchant_id', auth()->user()?->merchantProfile?->id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->visible(fn () => static::resolveMerchantRoleStrict() === 'clinic')
                ->dehydrated(true),
        ])->columns(2);
    }
    /**
     * Determine the merchant role strictly.
     * - If both Spatie role and merchant profile role exist, they MUST match.
     * - If only one exists and is valid ('groomer'|'clinic'), use it.
     * - If none or mismatch, throw a validation error (no default).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected static function resolveMerchantRoleStrict(): string
    {
        $user = auth()->user();

        // 1) Read role from Spatie (if available)
        $spatie = null;
        if ($user && method_exists($user, 'hasRole')) {
            if ($user->hasRole('groomer') || $user->hasRole('groomer_merchant')) {
                $spatie = 'groomer';
            } elseif ($user->hasRole('clinic') || $user->hasRole('clinic_merchant')) {
                $spatie = 'clinic';
            }
        }

        // 2) Read role from merchant profile (role|type|category)
        $profile = $user?->merchantProfile ?? null;
        $fromProfile = null;
        if ($profile) {
            $candidate = strtolower((string)($profile->role ?? $profile->type ?? $profile->category ?? ''));
            if (in_array($candidate, ['groomer','clinic'], true)) {
                $fromProfile = $candidate;
            }
        }

        // 3) Validate consistency
        if ($spatie && $fromProfile && $spatie !== $fromProfile) {
            throw ValidationException::withMessages([
                'role' => "Your account role '{$spatie}' does not match your merchant profile role '{$fromProfile}'. Please contact an administrator.",
            ]);
        }

        // 4) Choose the role if any is available
        $role = $spatie ?? $fromProfile;
        if (!$role) {
            throw ValidationException::withMessages([
                'role' => 'Unable to determine your staff role from account or merchant profile.',
            ]);
        }

        return $role;
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['merchant_id'] = $data['merchant_id'] ?? (auth()->user()?->merchantProfile?->id);
        $data['role'] = static::resolveMerchantRoleStrict(); // no default; strict check

        $behavior = StaffFactory::make($data['role']);
        return $behavior->prepare($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {

        // Enforce strict role based on user + merchant profile; no defaults allowed.
        $data['role'] = static::resolveMerchantRoleStrict();
        return $data;
    }

    public static function afterCreate(\App\Models\Staff $record, array $data): void
    {
        $behavior = StaffFactory::make($record->role);
        $behavior->afterCreate($record, $data);
    }

    public static function afterSave(\App\Models\Staff $record, array $data): void
    {
        $behavior = StaffFactory::make($record->role);
        $behavior->afterSave($record, $data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->columns([
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
