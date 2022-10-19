<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = "Manage";

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                    ->default(auth()->user()->id)
                    ->disabled()
                    ->hidden()
                    ->required(),
                Select::make('doctor_id')
                    ->options(User::all()->where('role_id', '3')->pluck('name', 'id'))
                    ->label('Doctor Name'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ])->required(),
                DatePicker::make('birthday')
                    ->required(),
                TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Select::make('category')
                    ->options([
                        'Dental' => 'Dental',
                        'Check Up' => 'Check Up',
                        'Medical' => 'Medical',
                        'Other' => 'Other',
                    ])->required(),
                Select::make('specification')
                    ->options([
                        'Child' => 'Child',
                        'Young' => 'Young',
                        'Teen' => 'Teen',
                        'Adult' => 'Adult',
                        'Senior' => 'Senior',
                        'Other' => 'Other',
                    ])->required(),
                DatePicker::make('date')
                    ->label('Appointment Date')
                    ->required(),
                Select::make('status')
                    ->options([
                        'cancelled' => 'Cancelled',
                        'pending' => 'Pending',
                        'success' => 'Success',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('gender')->toggleable(),
                Tables\Columns\TextColumn::make('category')->toggleable(),
                Tables\Columns\TextColumn::make('specification')->toggleable(),
                Tables\Columns\TextColumn::make('doctor.name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'success' => 'success',
                    ])->sortable()->searchable()->toggleable(),

            ])
            ->filters([
                Filter::make('appointment_at')
                    ->form([
                        DatePicker::make('appointment_from'),
                        DatePicker::make('appointment_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['appointment_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['appointment_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {

        if (auth()->user()->role_id == 3) {
            return parent::getEloquentQuery()
                ->where('doctor_id', auth()->user()->id)
                ->count();
        } else if (auth()->user()->role_id == 4) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id)
                ->count();
        } else {
            return self::getModel()::count();
        }
    }

    public static function getEloquentQuery(): Builder
    {
        // if role id of logged in user is 2, table must display all record with role id = 4
        // else, display all record
        if (auth()->user()->role_id == 3) {
            return parent::getEloquentQuery()
                ->where('doctor_id', auth()->user()->id);
        } else {
            // code here
            return parent::getEloquentQuery();
        }
    }
}
