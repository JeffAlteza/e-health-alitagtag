<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\DoctorSchedule;
use App\Models\User;
use Carbon\Carbon;
use Domain\Appointment\Models\Appointment;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Illuminate\Support\Facades\Cache;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Manage';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // for caching purposes, to load faster
        $options = Cache::remember('doctors_list', 60, function () {
            return User::where('role_id', '3')->pluck('name', 'id');
        });
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('queue_number')
                        ->disabled(),
                    TextInput::make('user_id')
                        ->default(Auth::id())
                        ->disabled(),
                    Select::make('doctor_id')
                        ->options($options)
                        ->label('Doctor Name')
                        ->disabled(Auth::user()->isPatient())
                        ->required(),
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
                            'PWD' => 'PWD'
                        ])->required(),
                    DatePicker::make('date')
                        ->label('Appointment Date')
                        ->disabled(Auth::user()->isPatient())
                        ->required(),
                    Select::make('time')
                        ->options([
                            'AM' => 'AM (Morning)',
                            'PM' => 'PM (Afternoon)',
                        ])
                        ->required(),
                    Select::make('status')
                        ->disabled(Auth::user()->isPatient())
                        ->options([
                            'Cancelled' => 'Cancelled',
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Completed' => 'Completed',
                        ])
                        ->default('Pending')
                        ->required(),
                    TextArea::make('cancelation_reason')
                        ->disabled()
                        ->columnSpan('full')
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Appointment No.'),
                TextColumn::make('queue_number')->label('Queue No.'),
                TextColumn::make('user.name'),
                TextColumn::make('name'),
                // TextColumn::make('gender'),
                TextColumn::make('category'),
                // TextColumn::make('specification'),
                TextColumn::make('doctor.name'),
                TextColumn::make('date')
                    ->date(),
                TextColumn::make('time'),
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
                    ->hidden(Auth::user()->isDoctor() || Auth::user()->isPatient())
                    ->options([
                        'Completed' => 'Completed',
                        'Approved' => 'Approved',
                        'Pending' => 'Pending',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->default('Pending'),
                Filter::make('date')
                    ->hidden(Auth::user()->isPatient())
                    ->form([
                        DatePicker::make('appointment_from')
                            ->default(Carbon::now()->subDay(1)),
                        DatePicker::make('appointment_until')
                            ->default(Carbon::now()),
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
                Action::make('reschedule')
                        ->icon('heroicon-s-calendar')
                        ->modalWidth('lg')
                        ->hidden(fn (Appointment $record) => $record->status == 'Completed' || $record->status == 'Cancelled')
                        ->action(function (Appointment $record, array $data) {
                            $record->update([
                                'date' => $data['date'],
                                'time' => $data['time'],
                            ]);

                            $user = auth()->user()->name;
                            Filament::notify(status: 'success', message: "**Good day {$user}!, your appointment has been successfully rescheduled.**");
                        })
                        ->form(function (Appointment $record) {
                            $available_schedules = DoctorSchedule::where('doctor_id', $record->doctor_id)
                                ->where('status', 'available')
                                ->get();
                            $doctor = User::where('id', $record->doctor_id)->pluck('name');
                            return [
                                TextInput::make('Doctor')
                                    ->default($doctor)
                                    ->disabled(),
                                Select::make('date')
                                    ->label('Available dates')
                                    ->options($available_schedules->pluck('date', 'date'))
                                    ->required(),
                                Select::make('time')
                                    ->options([
                                        'AM' => 'AM (Morning)',
                                        'PM' => 'PM (Afternoon)',
                                    ])
                                    ->required()
                            ];
                        }),
                ActionGroup::make([
                    ViewAction::make()->color('warning'),
                    EditAction::make()
                        ->hidden(fn (Appointment $record) => Auth::user()->isPatient() && ($record->status == 'Completed' || $record->status == 'Cancelled')),
                    Action::make('cancel')
                        ->hidden(fn (Appointment $record) => $record->status == 'Completed' || $record->status == 'Cancelled')
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
                                    'cancelation_reason' => $data['cancelation_reason'],
                                ]);
                                Filament::notify(status: 'success', message: 'Appointment Cancelled');
                            }
                        )
                        ->form([
                            Textarea::make('cancelation_reason')
                                ->required(),
                        ]),
                    Action::make('pending')
                        ->hidden(fn (Appointment $record) => $record->status == 'Pending' || $record->status == 'Cancelled' || Auth::user()->isPatient())
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
                        ->hidden((fn (Appointment $record) => $record->status != 'Approved' || Auth::user()->isPatient()))
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
                        ->hidden(fn (Appointment $record) => $record->status != 'Pending' || Auth::user()->isPatient())
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
                    ->hidden(Auth::user()->isPatient()),
            ]);
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
        if (Auth::user()->isPatient()) {
            return parent::getEloquentQuery()
                ->where('user_id', Auth::id())
                ->count();
        }

        if (Auth::user()->isDoctor()) {
            return parent::getEloquentQuery()
                ->where('doctor_id', Auth::id())
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
        if (Auth::user()->isPatient()) {
            return parent::getEloquentQuery()
                ->where('user_id', Auth::id());
        }

        if (Auth::user()->isDoctor()) {
            return parent::getEloquentQuery()
                ->where('doctor_id', Auth::id())
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
