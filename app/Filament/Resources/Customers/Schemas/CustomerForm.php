<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure($schema)
    {
        return $schema->schema(self::schema())->columns(3);
    }
    public static function schema(): array
    {
        return [
            Section::make('Customer Details')
                ->make([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required(),
                    TextInput::make('phone')
                        ->required()
                        ->tel(),
                    Textarea::make('address')
                        ->columnSpanFull(),

                ])
                ->columnSpan(2),
            Section::make('Details')
            ->schema([
                
                    Toggle::make('is_vip')
                        ->required(),
                    FileUpload::make('image')->avatar(),
                Select::make('gender')
                ->options([
                    'male',
                    'female'
                ]),
                TextInput::make('messenger'),
                TextInput::make('occupation'),
                TextInput::make('target_area'),
                TextInput::make('health_history'),
                TextInput::make('pain_upon_cunsoltation'),
            ])
        ];
    }
}
