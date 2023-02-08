<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\DoctorScheduleResource\Pages;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Savannabits\Flatpickr\Flatpickr;

class DoctorScheduleResource extends Resource
{
    protected static ?string $model = DoctorSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Manage';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'doctor_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('category')
                        ->options(
                            [
                                'Dental' => 'Dental',
                                'Medical/Check Up' => 'Medical/Check Up',
                                'OB' => 'OB'
                            ]
                        )
                        ->label('Category')
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('doctor_id', null))
                        ->required(),

                    Select::make('doctor_id')
                        ->options(
                            function (callable $get) {
                                return User::all()->where('category', $get('category'))->pluck('name', 'id');
                            }
                        )
                        ->label('Doctor Name')
                        ->disabled(fn (Closure $get) => $get('category') == null)
                        ->required(),

                    DatePicker::make('date')
                        ->label('Date Availability')
                        ->default(now())
                        ->required(),

                    // TextInput::make('time_start')
                    //     ->required(),

                    // TextInput::make('time_end')
                    //     ->required(),

                    Select::make('time_start')
                        ->options([
                            '08:00 AM' => '08:00 AM',
                            '09:00 AM' => '09:00 AM',
                            '10:00 AM' => '10:00 AM',
                            '11:00 AM' => '11:00 AM',
                            '12:00 PM' => '12:00 PM',
                            '01:00 PM' => '01:00 PM',
                            '02:00 PM' => '02:00 PM',
                            '03:00 PM' => '03:00 PM',
                        ])->required(),

                    Select::make('time_end')
                        ->options([
                            '09:00 AM' => '09:00 AM',
                            '10:00 AM' => '10:00 AM',
                            '11:00 AM' => '11:00 AM',
                            '12:00 PM' => '12:00 PM',
                            '01:00 PM' => '01:00 PM',
                            '02:00 PM' => '02:00 PM',
                            '03:00 PM' => '03:00 PM',
                            '04:00 PM' => '04:00 PM',
                            '05:00 PM' => '05:00 PM',
                        ])->required(),

                    Select::make('status')
                        ->options([
                            'available' => 'Available',
                            'unavailable' => 'Unavailable',
                            'full' => 'Full',
                        ])->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.name'),
                TextColumn::make('category'),
                TextColumn::make('date')->date(),
                TextColumn::make('time_start'),
                TextColumn::make('time_end'),
                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'unavailable',
                        'primary' => 'available',
                    ]),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Filter::make('schedule_at')
                    ->form([
                        DatePicker::make('schedule_from')
                            ->default(Carbon::now()->subDay(1)),
                        DatePicker::make('schedule_until')
                            ->default(Carbon::now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['schedule_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['schedule_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Action::make('appointment')
                    ->disabled(fn (DoctorSchedule $record) => $record->status == 'unavailable' || $record->status == 'full')
                    ->modalWidth('lg')
                    ->icon('heroicon-s-document-text')
                    ->action(function (DoctorSchedule $record, array $data): void {
                        //you can use $record for fill appointment columns
                        Appointment::create([
                            'name' => $data['name'],
                            'gender' => $data['gender'],
                            'birthday' => $data['birthday'],
                            'phone_number' => $data['phone_number'],
                            'specification' => $data['specification'],
                            // 'date' => TextColumn::get,
                            'status' => 'Pending',
                            'user_id' => auth()->user()->id,
                            'category' => $record->category,
                            'doctor_id' => $record->doctor_id,
                            'date' => $record->date,
                        ]);

                        $user = auth()->user()->name;
                        Filament::notify(status: 'success', message: "**Good day {$user}!, you have successfully book an appointment.**");
                    })
                    ->form([
                        TextInput::make('name')
                            ->default(auth()->user()->name)
                            ->required(),
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
                        Select::make('specification')
                            ->options([
                                'Infant' => 'Infant',
                                'Child' => 'Child',
                                'Teen' => 'Teen',
                                'Adult' => 'Adult',
                                'Senior' => 'Senior',
                                'PWD' => 'PWD',
                            ])->required(),
                        Select::make('time')
                            ->options([
                                'AM' => 'AM (Morning)',
                                'PM' => 'PM (Afternoon)',
                            ])->required()

                    ])
                    ->modalHeading('Appointment')
                    ->modalSubheading('Fill up all the corresponding fields'),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('full')
                        ->hidden(fn (DoctorSchedule $record) => $record->status != 'available' || Auth::user()->isPatient())
                        ->requiresConfirmation()
                        ->icon('heroicon-s-thumb-up')
                        ->color('primary')
                        ->action(
                            function (DoctorSchedule $record, array $data): void {
                                $record->update([
                                    'status' => 'full',
                                ]);
                                Filament::notify(status: 'success', message: 'Doctor schedule updated successfully!');
                            }
                        ),
                ]),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')->hidden(Auth::user()->isPatient()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctorSchedules::route('/'),
            'create' => Pages\CreateDoctorSchedule::route('/create'),
            'edit' => Pages\EditDoctorSchedule::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        $date = Carbon::now()->toDateString();
        if (Auth::user()->isPatient()) {
            return parent::getEloquentQuery()
                ->where('date', '>=', $date)->count();
        } else {
            return self::getModel()::whereDate('date', $date)->count();
        }
    }

    public static function getEloquentQuery(): Builder
    {
        // if role id of logged in user is 2, table must display all record with role id = 4
        // else, display all record
        $date = Carbon::now();
        if (Auth::user()->isPatient()) {
            return parent::getEloquentQuery()
                ->whereDate('date', '>=', $date);
        } else {
            return parent::getEloquentQuery();
        }
    }
}
