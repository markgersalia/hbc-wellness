<?php

namespace App\Filament\Resources\Therapists\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TherapistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make([
                    
                FileUpload::make('image')
                ->avatar(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('bio')
                    ->required(),  
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                    
                ])->columnSpan(2),
                
                Section::make([
                    TextEntry::make('average_rating')
                    ->size('lg')
                    ->default(fn($record)=>$record->getRating())
                ])->visible(fn($record)=>$record),
            ])->columns(3);
    }
}
