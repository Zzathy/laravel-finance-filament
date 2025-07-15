<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Account Name'),
                Select::make('type')
                    ->options([
                        'Bank Account' => 'Bank Account',
                        'E-Wallet' => 'E-Wallet',
                        'Cash' => 'Cash',
                        'Credit Card' => 'Credit Card',
                        'Investment' => 'Investment',
                    ])
                    ->default('Bank Account')
                    ->label('Account Type'),
                Select::make('currency')
                    ->options([
                        'IDR' => 'IDR',
                        'USD' => 'USD',
                    ])
                    ->default('IDR')
                    ->label('Currency'),
                TextInput::make('initial_balance')
                    ->numeric()
                    ->default(0.00)
                    ->label('Initial Balance')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Account Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Account Type')
                    ->sortable(),
                TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable(),
                TextColumn::make('initial_balance')
                    ->label('Initial Balance')
                    ->numeric(
                        decimalPlaces: 2,
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Current Balance')
                    ->getStateUsing(fn (Account $record) => $record->getBalance())
                    ->numeric(
                        decimalPlaces: 2,
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
