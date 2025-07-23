<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class QuickEntryWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.quick-entry-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->default(0.00)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->required(),
                    Select::make('account_id')
                        ->options(Account::all()->pluck('name', 'id'))
                        ->label('Account')
                        ->required(),
                    Select::make('category_id')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->label('Category')
                        ->required(),
                    TextInput::make('description')
                        ->label('Description')
                        ->nullable(),
                ])
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $formData = $this->form->getState();

        $formData['user_id'] = Auth::id();
        $formData['type'] = 'Expense';
        $formData['transaction_date'] = now();

        Transaction::create($formData);

        $this->form->fill();

        Notification::make()
            ->title('Expense added successfully')
            ->success()
            ->send();
    }
}