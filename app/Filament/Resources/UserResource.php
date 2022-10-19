<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Roles;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = "Manage";

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(
                        ignorable: fn (null |Model $record): null |Model => $record,
                    ),
                Select::make('role_id')
                    ->options([
                        '2' => 'Nurse',
                        '3' => 'Doctor',
                        '4' => 'Patient',
                    ])
                    ->required()
                    ->label('Roles'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role.name')->sortable()->searchable()->label('Roles'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        // if role id of logged in user is 2, table must display all record with role id = 4
        // else, display all record
        if (auth()->user()->role_id == 2) {
            return parent::getEloquentQuery()
                ->where('role_id', 4);
        } else {
            // code here
            return parent::getEloquentQuery();
        }
    }
}
