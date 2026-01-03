<?php

namespace App\Filament\Resources\Beds;

use App\Filament\Clusters\Booking\BookingCluster;
use App\Filament\Resources\Beds\Pages\ManageBeds;
use App\Models\Bed;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class BedResource extends Resource
{
    protected static ?string $model = Bed::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'bed';
    protected static ?string $cluster = BookingCluster::class;
    protected static ?int $navigationSort = 3;
    // protected static UnitEnum|string|null $navigationGroup = 'Booking Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_available')->default(true),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->placeholder("Add bed description"),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bed')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description'),
                ToggleColumn::make('is_available'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBeds::route('/'),
        ];
    }
}
