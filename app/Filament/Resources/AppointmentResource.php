<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Manage';

    protected static ?string $recordTitleAttribute = 'name';


    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('user_id')
                        ->default(auth()->user()->id)
                        ->disabled(),
                    // ->hidden(),
                    // Select::make('doctor.name')
                    //     ->required(),
                    Select::make('doctor_id')
                        ->options(User::all()->where('role_id', '3')->pluck('name', 'id'))
                        ->label('Doctor Name')
                        ->disabled(auth()->user()->role_id == 4)
                        ->required(),
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
                            'Medical/Checkup' => 'Medical/Check Up',
                            'OB' => 'OB',
                            'Other' => 'Other',
                        ])->required(),
                    Select::make('specification')
                        ->options([
                            'Infant' => 'Infant',
                            'Child' => 'Child',
                            'Teen' => 'Teen',
                            'Adult' => 'Adult',
                            'Senior' => 'Senior',
                            'PWD' => 'PWD',
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
                            'Approved' => 'Approved',
                            'Completed' => 'Completed',
                        ])
                        ->default('Pending')
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Appointment No.'),
                TextColumn::make('user.name'),
                TextColumn::make('name'),
                // TextColumn::make('gender'),
                TextColumn::make('category'),
                // TextColumn::make('specification'),
                TextColumn::make('doctor.name'),
                TextColumn::make('date')
                    ->date(),
                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Cancelled',
                        'warning' => 'Pending',
                        'success' => 'Completed',
                        'primary' => 'Approved',
                    ]),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->hidden(auth()->user()->role_id == 4 || 3)
                    ->options([
                        'Completed' => 'Completed',
                        'Approved' => 'Approved',
                        'Pending' => 'Pending',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->default('Pending'),
                Filter::make('date')
                    ->hidden(auth()->user()->role_id == 4)
                    ->form([
                        DatePicker::make('appointment_from')
                            ->default(Carbon::now()->subDay(1)),
                        DatePicker::make('appointment_until')
                            ->default(Carbon::now()),
                    ])
                    // ->query(function (Builder $query, array $data): Builder {
                    //     return $query
                    //         ->when(
                    //             $data['appointment_from'],
                    //             fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                    //         )
                    //         ->when(
                    //             $data['appointment_until'],
                    //             fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                    //         );
                    // }),
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
                ActionGroup::make([
                    ViewAction::make()->color('warning'),
                    EditAction::make()
                        ->hidden(fn (Appointment $record) => auth()->user()->role_id == 4 && ($record->status == 'Completed' || $record->status == 'Cancelled')),

                    Action::make('cancel')
                        // ->disabled(fn (Appointment $record) => $record->status == 'Completed' || $record->status == 'Cancelled')
                        ->hidden(fn (Appointment $record) => $record->status == 'Completed' || $record->status == 'Cancelled')
                        // ->hidden(auth()->user()->role_id != 4)
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Appointment')
                        ->modalSubheading('Are you sure you\'d like to Cancel these appointment? This cannot be undone.')
                        ->modalButton('Proceed')
                        ->icon('heroicon-s-x-circle')
                        ->color('danger')
                        ->action(
                            function (Appointment $record, array $data): void {
                                $record->update([
                                    'status' => 'Cancelled',
                                ]);
                                Filament::notify(status: 'success', message: 'Appointment Cancelled');
                            }
                        ),
                    Action::make('pending')
                        ->hidden(fn (Appointment $record) => $record->status == 'Pending' || $record->status == 'Cancelled' || auth()->user()->role_id == 4)
                        ->requiresConfirmation()
                        ->icon('heroicon-s-minus-circle')
                        ->color('warning')
                        ->action(
                            function (Appointment $record, array $data): void {
                                $record->update([
                                    'status' => 'Pending',
                                ]);
                                Filament::notify(status: 'success', message: 'Appointment status changed to Pending');
                            }
                        ),
                    Action::make('completed')
                        ->hidden((fn (Appointment $record) => $record->status != 'Approved' || auth()->user()->role_id == 4))
                        ->requiresConfirmation()
                        ->icon('heroicon-s-check-circle')
                        ->color('primary')
                        ->action(
                            function (Appointment $record, array $data): void {
                                $record->update([
                                    'status' => 'Completed',
                                ]);
                                Filament::notify(status: 'success', message: 'Appointment Completed');
                            }
                        ),
                    Action::make('approved')
                        ->hidden(fn (Appointment $record) => $record->status != 'Pending' || auth()->user()->role_id == 4)
                        ->requiresConfirmation()
                        ->icon('heroicon-s-thumb-up')
                        ->color('primary')
                        ->action(
                            function (Appointment $record, array $data): void {
                                $record->update([
                                    'status' => 'Approved',
                                ]);
                                Filament::notify(status: 'success', message: 'Appointment Approved!');
                            }
                        ),
                ]),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export'),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Export All')
                    ->hidden(auth()->user()->role_id == 4),
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
        $date = Carbon::now()->toDateString();
        if (auth()->user()->role_id == 4) {
            // dd('patient');
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id)
                ->count();
        }

        if (auth()->user()->role_id == 3) {
            // dd('patient');
            return parent::getEloquentQuery()
                ->where('doctor_id', auth()->user()->id)
                ->where('date', $date)
                ->where('status', 'Approved')
                ->count();
        }

        return parent::getEloquentQuery()
            ->where('date', $date)
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        // if role id of logged in user is 4, table must display all record with role id = 4
        // else, display all record

        if (auth()->user()->role_id == 4) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->user()->id);
        }

        if (auth()->user()->role_id == 3) {
            return parent::getEloquentQuery()
                ->where('doctor_id', auth()->user()->id)
                ->where('status', 'Approved');
        }

        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function getWidgets(): array
    {
        return [
            AppointmentResource\Widgets\MyCalendar::class,
        ];
    }
}
