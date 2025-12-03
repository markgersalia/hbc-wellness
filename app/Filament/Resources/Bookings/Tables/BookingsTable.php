<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::schema())
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
                   Action::make('Make Payment')
                ->schema(BookingPaymentResource::schema())
                ->action(function ($record, array $data): void { 
                    $record->payments()->create($data);
                })    
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
                ImageColumn::make('listing.images'),
                TextColumn::make('listing.title')
                    ->numeric() 
                    ->sortable(), 
                TextColumn::make('customer.name') 
                    ->searchable() 
                    ->sortable(),   
                TextColumn::make('therapist.name'),
                TextColumn::make('price')
                    ->numeric() 
                    ->sortable(),  
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('user.name')
                    ->label("Processed By")
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
