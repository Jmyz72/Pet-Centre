<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Services\ReleaseCodeService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Bookings';

    public static function getEloquentQuery(): Builder
    {
        // Scope to current merchant's bookings
        $merchantId = auth()->user()->merchantProfile?->id;
        
        return parent::getEloquentQuery()
            ->where('merchant_id', $merchantId)
            ->with(['customer', 'service', 'package', 'customerPet', 'merchantPet', 'staff']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->disabled()
                    ->label('Booking ID'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_at')
                    ->required(),
                Forms\Components\TextInput::make('price_amount')
                    ->numeric()
                    ->prefix('RM')
                    ->step(0.01),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'info',
                        'package' => 'success',
                        'adoption' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('service.title')
                    ->label('Service')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->label('Start')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_amount')
                    ->money('MYR')
                    ->label('Amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Staff')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed', 
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('booking_type')
                    ->options([
                        'service' => 'Service',
                        'package' => 'Package',
                        'adoption' => 'Adoption',
                    ])
                    ->label('Type'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('release_payment')
                    ->label('Release Payment')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->status !== 'completed')
                    ->form([
                        Forms\Components\TextInput::make('code')
                            ->label('6-Digit Release Code')
                            ->placeholder('Enter customer\'s release code')
                            ->required()
                            ->maxLength(6)
                            ->minLength(6)
                            ->numeric()
                            ->helperText('Ask the customer for their 6-digit release code. Codes expire in 30 minutes.'),
                    ])
                    ->action(function (Booking $record, array $data, ReleaseCodeService $service) {
                        try {
                            $service->release($record, $data['code']);

                            Notification::make()
                                ->title('Payment Released Successfully!')
                                ->body('Booking has been completed and payment released.')
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Release Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}