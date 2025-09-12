<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditContactMessage extends EditRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('archive')
                ->label('Archive')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->action(function () {
                    $this->record->update(['status' => 'archived']);
                    $this->refreshFormData(['status']);
                })
                ->hidden(fn () => $this->record->status === 'archived'),
            Actions\Action::make('unarchive')
                ->label('Unarchive')
                ->icon('heroicon-o-arrow-up-tray') // Corrected icon name
                ->color('success')
                ->action(function () {
                    $this->record->update(['status' => 'read']);
                    $this->refreshFormData(['status']);
                })
                ->visible(fn () => $this->record->status === 'archived'),
            Actions\DeleteAction::make(),
        ];
    }
}