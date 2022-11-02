<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Roles;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
                Card::make()->schema([
                    TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignorable: fn (null |Model $record): null |Model => $record,),

                TextInput::make('password')
                    ->password()
                    ->required()
                    ->rule(Password::default())
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->visible(fn (?User $record) => $record === null || !$record->exists),

                TextInput::make('password_confirmation')
                    ->required()
                    ->password()
                    ->same('password')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->visible(fn (?User $record) => $record === null || !$record->exists),

                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required()
                    ->label('Roles'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role.name')
                    ->label('Roles'),
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('updated_at')
                    ->date(),
                // BadgeColumn::make('status')
                //     ->colors([
                //         'danger' => 'inactive',
                //         'success' => 'active',
                //     ]),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                SelectFilter::make('role_id')
                    ->relationship('role', 'name'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
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
            // ->where('role_id', '!=', 1);
        }
    }
}
