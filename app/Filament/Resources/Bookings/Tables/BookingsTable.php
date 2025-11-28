<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                DeleteAction::make(),
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
                ImageColumn::make('listing.images')
                    ->label('')
                    ->visible(config('booking.has_listings')),
                TextColumn::make('listing.title')
                    ->numeric()
                    ->visible(config('booking.has_listings'))
                    ->sortable(),
                    
                TextColumn::make('title')
                    ->numeric()
                    ->visible(!config('booking.has_listings'))
                    ->sortable(),
                TextColumn::make('price')
                    ->numeric()
                    ->visible(!config('booking.has_listings'))
                    ->sortable(),
                TextColumn::make('type')
                    ->numeric()
                    ->visible(!config('booking.has_listings'))
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label("Processed By")
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
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
