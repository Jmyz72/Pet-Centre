<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditTrailResource\Pages;
use App\Models\ActivityLog\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model; // Import the base Model

class AuditTrailResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'User Activity';
    protected static ?string $navigationLabel = 'Audit Trail';

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; } // Corrected signature
    public static function canDelete(Model $record): bool { return false; } // Corrected signature

    public static function getEloquentQuery(): Builder
    {
        // Eager load both relationships for efficiency
        return parent::getEloquentQuery()->with(['causer', 'subject']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // WHO: The user who performed the action.
                Tables\Columns\TextColumn::make('causerName') // Use the new accessor
                    ->label('User Name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('causer', 'App\Models\User', 
                            fn (Builder $q) => $q->where('name', 'like', "%{$search}%")
                        );
                    }),

                // --- ADDED USER EMAIL COLUMN ---
                Tables\Columns\TextColumn::make('causerEmail') // Use the new accessor
                    ->label('User Email')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('causer', 'App\Models\User', 
                            fn (Builder $q) => $q->where('email', 'like', "%{$search}%")
                        );
                    }),

                // WHAT: A clean description of the action.
                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->searchable(),

                // ON WHAT: The specific item that was affected.
                Tables\Columns\TextColumn::make('subjectDescription') // Use the new accessor
                    ->label('Subject')
                    ->searchable(),

                // WHEN: The date and time of the action.
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function form(Form $form): Form
    {
        // This is for the "View" modal/page.
        return $form->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\TextInput::make('causerName')->label('User Name'),
                    Forms\Components\TextInput::make('causerEmail')->label('User Email'),
                    Forms\Components\TextInput::make('description')->label('Action'),
                    Forms\Components\TextInput::make('subjectDescription')->label('Subject'),
                    Forms\Components\DateTimePicker::make('created_at')->label('Date'),
                ])->columns(2),

            Forms\Components\KeyValue::make('properties.attributes')
                ->label('New Values')
                ->columnSpanFull()
                ->hidden(fn ($record) => $record->event !== 'updated'),

            Forms\Components\KeyValue::make('properties.old')
                ->label('Old Values')
                ->columnSpanFull()
                ->hidden(fn ($record) => $record->event !== 'updated'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditTrails::route('/'),
            'view' => Pages\ViewAuditTrail::route('/{record}'),
        ];
    }
}