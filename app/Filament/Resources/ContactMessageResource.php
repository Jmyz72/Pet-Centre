<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationLabel = 'Contact Messages';
    protected static ?string $navigationGroup = 'Support Management';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Admin Management')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'read' => 'Read',
                                'replied' => 'Replied',
                                'archived' => 'Archived',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->rows(3)
                            ->placeholder('Add internal notes about this message...'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'new',
                        'warning' => 'read',
                        'success' => 'replied',
                        'gray' => 'archived',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Received'),
                Tables\Columns\TextColumn::make('replied_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'replied' => 'Replied'
                    ]),
                
                // Toggle filter to show/hide archived messages
                Tables\Filters\Filter::make('show_archived')
                    ->label('Show Archived Messages')
                    ->query(function (Builder $query, array $data) {
                        if ($data['show_archived'] ?? false) {
                            return $query; // Show all messages including archived
                        }
                        
                        return $query->where('status', '!=', 'archived'); // Hide archived by default
                    })
                    ->form([
                        Forms\Components\Toggle::make('show_archived')
                            ->label('Show archived messages')
                            ->default(false)
                            ->live(),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('archive')
                    ->label('Archive')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->action(function (ContactMessage $record) {
                        $record->update(['status' => 'archived']);
                    })
                    ->hidden(fn (ContactMessage $record) => $record->status === 'archived'),
                Tables\Actions\Action::make('unarchive')
                    ->label('Unarchive')
                    ->icon('heroicon-o-arrow-up-tray') // Corrected icon name
                    ->color('success')
                    ->action(function (ContactMessage $record) {
                        $record->update(['status' => 'read']);
                    })
                    ->visible(fn (ContactMessage $record) => $record->status === 'archived'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Archive Selected')
                        ->icon('heroicon-o-archive-box')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'archived']);
                        }),
                    Tables\Actions\BulkAction::make('unarchive')
                        ->label('Unarchive Selected')
                        ->icon('heroicon-o-arrow-up-tray') // Corrected icon name
                        ->action(function ($records) {
                            $records->each->update(['status' => 'read']);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '!=', 'archived')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $count = static::getModel()::where('status', 'new')->count();
        return $count > 0 ? 'primary' : 'gray';
    }
}
