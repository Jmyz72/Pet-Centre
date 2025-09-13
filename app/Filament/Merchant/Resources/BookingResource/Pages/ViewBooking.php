<?php

namespace App\Filament\Merchant\Resources\BookingResource\Pages;

use App\Filament\Merchant\Resources\BookingResource;
use App\Services\ReleaseCodeService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Notifications\Notification;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('release_payment')
                ->label('Release Payment')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'completed')
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
                ->action(function (array $data, ReleaseCodeService $service) {
                    try {
                        $service->release($this->record, $data['code']);

                        Notification::make()
                            ->title('Payment Released Successfully!')
                            ->body('Booking has been completed and payment released.')
                            ->success()
                            ->send();

                        // Refresh the record to show updated status
                        $this->record->refresh();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Release Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}