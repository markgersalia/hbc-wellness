<?php

namespace App\Livewire;

use App\Models\Booking;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RegistrationForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registration Details')
                    ->description('Please fill in all required fields.')
                    ->schema([
                        Grid::make(2) // This forces a 2-column layout
                            ->schema([
                                TextInput::make('title_one')
                                    ->label('First Title')
                                    ->required(),
                                TextInput::make('title_two')
                                    ->label('Second Title')
                                    ->required(),
                            ]),
                        TextInput::make('title_three')
                            ->label('Full Width Title')
                            ->required(),
                    ])
            ])
            ->statePath('data')
            ->model(Booking::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Booking::create($data);

        $this->form->model($record)->saveRelationships();
    }

    public function render(): View
    {
        return view('livewire.home.registration-form');
    }
}
