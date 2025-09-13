<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\MerchantApplication;
use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Platform Activities';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['customer', 'merchant', 'service', 'package'])
                    ->latest()
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Booking ID')
                    ->prefix('#')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('merchant.business_name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('service.title')
                    ->label('Service/Package')
                    ->placeholder(fn ($record) => $record->package?->name ?? 'Adoption')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('booking_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'info',
                        'package' => 'success', 
                        'adoption' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('price_amount')
                    ->label('Amount')
                    ->money('MYR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Booking $record): string => route('filament.admin.resources.bookings.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}