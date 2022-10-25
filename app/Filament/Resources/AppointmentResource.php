<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Livewire;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = "Manage";

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                    ->default(auth()->user()->id)
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
                        'Cancelled' => 'Cancelled',
                        'Pending' => 'Pending',
                        'Success' => 'Success',
                    ])
                    ->default('Pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('specification'),
                Tables\Columns\TextColumn::make('doctor.name'),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Cancelled',
                        'warning' => 'Pending',
                        'success' => 'Success',
                    ]),

            ])
            ->defaultSort('date','desc')
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
                // ->hidden(auth()->user()->role_id == 4),
                Action::make('cancel')
                    // ->hidden(auth()->user()->role_id != 4)
                    // ->disabled(fn (Livewire $ListAppointments) => $ListAppointments->record->status == 'Success')
                    ->icon('heroicon-s-x-circle')
                    ->color('danger')
                    ->action(
                        function (Appointment $record, array $data): void {
                            $record->update([
                                'status' => 'Cancelled',
                            ]);
                            Filament::notify(status: 'success', message: 'Cancelled Appointment');
                        }
                    )
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

        if (auth()->user()->role_id == 4) {
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
        if (auth()->user()->role_id == 4) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id);
        }
        if (auth()->user()->role_id == 2) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id);
        } else {
            // code here
            return parent::getEloquentQuery();
        }
    }


}
