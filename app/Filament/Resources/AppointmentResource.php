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
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
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
                // TextInput::make('doctor.name')
                //     ->disabled()
                //     ->required(),
                // Select::make('doctor_id')
                // ->relationship('user','name')
                // ->options(User::all()->where('role_id', '3')->pluck('name', 'id'))
                // ->label('Doctor Name'),
                // ->disabled(auth()->user()->role_id = 4),
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
                    ->disabled(auth()->user()->role_id == 4)
                    ->required(),
                Select::make('status')
                    ->disabled(auth()->user()->role_id == 4)
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
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Success' => 'Success',
                        'Pending' => 'Pending',
                        'Cancelled' => 'Cancelled',
                    ]),
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
                EditAction::make()
                    ->disabled(fn (Appointment $record) => auth()->user()->role_id == 4 && ($record->status == 'Success' || $record->status == 'Cancelled')),
                Action::make('cancel')
                    ->disabled(fn (Appointment $record) => $record->status == 'Success' || $record->status == 'Cancelled')
                    ->hidden(auth()->user()->role_id != 4)
                    ->requiresConfirmation()
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
                // Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export'),
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
            // dd('patient');
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id)
                ->count();
        }
        // dd('admin');
        return self::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        // if role id of logged in user is 4, table must display all record with role id = 4
        // else, display all record

        if (auth()->user()->role_id == 4) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id);
        }
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }
}
