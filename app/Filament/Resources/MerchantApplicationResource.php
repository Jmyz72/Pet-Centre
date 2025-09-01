<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerchantApplicationResource\Pages;
use App\Models\MerchantApplication;
use App\Notifications\MerchantApplicationStatusNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MerchantApplicationResource extends Resource
{
    protected static ?string $model = MerchantApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('role')->label('Role'),
                Tables\Columns\TextColumn::make('phone')->label('Phone'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('registration_number')->label('Registration No.'),
                Tables\Columns\TextColumn::make('created_at')->label('Applied At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('')
                    ->modalContent(function (MerchantApplication $record) {
                        return view('admin.merchant_applications.view', [
                            'record' => $record,
                            'showActions' => $record->status === 'pending',
                        ]);
                    }),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action(function (MerchantApplication $record) {
                        if ($record->status !== 'pending') {
                            return;
                        }

                        // Approve the application & prevent reapply
                        $record->status = 'approved';
                        $record->can_reapply = 0;
                        $record->save();

                        // Sync key data to merchant_profiles (create if missing)
                        \App\Models\MerchantProfile::firstOrCreate(
                            ['user_id' => $record->user_id],
                            [
                                'role' => $record->role,
                                'name' => $record->name,
                                'phone' => $record->phone,
                                'address' => $record->address,
                                'registration_number' => $record->registration_number,
                                'license_number' => $record->license_number,
                                'document_path' => $record->document_path,
                            ]
                        );

                        // Notify the user (email + database)
                        $user = \App\Models\User::find($record->user_id);
                        if ($user) {
                            $user->notify(new MerchantApplicationStatusNotification(
                                status: 'approved',
                                reason: null,
                                applicationId: $record->id,
                                applicationName: $record->name,
                                role: $record->role,
                            ));
                        }
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->visible(fn (MerchantApplication $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->action(function (MerchantApplication $record, array $data) {
                        if ($record->status !== 'pending') {
                            return;
                        }

                        $record->status = 'rejected';
                        $record->rejection_reason = $data['rejection_reason'] ?? null;
                        $record->can_reapply = ! empty($data['can_reapply']); // true if checked
                        $record->save();

                        // Notify the user (email + database)
                        $user = \App\Models\User::find($record->user_id);
                        if ($user) {
                            $user->notify(new MerchantApplicationStatusNotification(
                                status: 'rejected',
                                reason: $data['rejection_reason'] ?? null,
                                applicationId: $record->id,
                                applicationName: $record->name,
                                role: $record->role,
                            ));
                        }
                    })
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required(),
                        Forms\Components\Checkbox::make('can_reapply')
                            ->label('Allow user to reapply?'),
                    ])
                    ->color('danger')
                    ->visible(fn (MerchantApplication $record) => $record->status === 'pending'),
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
            'index' => Pages\ListMerchantApplications::route('/'),
            // 'create' => Pages\CreateMerchantApplication::route('/create'),
            'edit' => Pages\EditMerchantApplication::route('/{record}/edit'),
        ];
    }
}
