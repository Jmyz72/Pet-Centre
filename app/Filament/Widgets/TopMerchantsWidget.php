<?php

namespace App\Filament\Widgets;

use App\Models\MerchantProfile;
use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopMerchantsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Performing Merchants';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MerchantProfile::query()
                    ->withCount(['bookings as total_bookings'])
                    ->withSum(['bookings as total_revenue' => function (Builder $query) {
                        $query->where('status', 'completed');
                    }], 'price_amount')
                    ->orderByDesc('total_revenue')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Merchant Name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'clinic' => 'success',
                        'groomer' => 'info',
                        'shelter' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('total_bookings')
                    ->label('Total Bookings')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Total Revenue')
                    ->money('MYR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->label('Contact')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (MerchantProfile $record): string => route('filament.admin.resources.merchant-profiles.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}