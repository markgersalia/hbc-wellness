<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\BookingStatus;
use App\Filament\Actions\BookingActions;
use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use App\PaymentStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::schema())
            ->filters([
                //
                SelectFilter::make('payment_status')
                ->options(PaymentStatus::class),
                SelectFilter::make('status')
                ->options(BookingStatus::class)
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
                BookingActions::makePayment()
                ->color('primary')
                ->icon(Heroicon::CurrencyDollar)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function schema(): array
    {
        return
            [
                TextColumn::make('status')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'canceled' => 'danger',
                            'completed' => 'success',
                            default => 'info',
                        })
                        ->sortable(),
                TextColumn::make('payment_status')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            'pending' => 'warning',
                            'paid' => 'success',
                            'failed' => 'danger',
                            default => 'gray',
                        })
                        ->sortable(),
                ImageColumn::make('listing.images')->label(""),
                TextColumn::make('listing.title')
                    ->numeric() 
                    ->sortable(), 
                TextColumn::make('customer.name') 
                    ->searchable() 
                    ->sortable(),   
                TextColumn::make('therapist.name'),
                TextColumn::make('price')
                    ->money('PHP') 
                    ->sortable(),  
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label("Processed By")
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ];
    }
}
