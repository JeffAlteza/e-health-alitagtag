<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\DoctorScheduleResource\Pages;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use Carbon\Carbon;
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
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
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
                    Select::make('doctor_id')
                        ->options(User::all()->where('role_id', '3')->pluck('name', 'id'))
                        ->label('Doctor Name')
                        ->required(),

                    Select::make('category')
                        ->options([
                            'Dental' => 'Dental',
                            'Medical/Checkup' => 'Medical/Check Up',
                            'OB' => 'OB',
                            'Other' => 'Other',
                        ])->required(),

                    DatePicker::make('date')
                        ->default(now())
                        ->required(),

                    // Flatpickr::make('read_at')->default(now())->enableTime(),
                    TextInput::make('time_start')
                        ->required(),

                    TextInput::make('time_end')
                        ->required(),

                    Select::make('status')
                        ->options([
                            'available' => 'Available',
                            'unavailable' => 'Unavailable',
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
                        ->default(Carbon::now()),
                        DatePicker::make('schedule_until'),
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
                EditAction::make(),
                DeleteAction::make(),
                Action::make('book')
                    ->disabled(fn (DoctorSchedule $record) => $record->status == 'unavailable')
                    ->modalWidth('lg')
                    ->icon('heroicon-s-document-text')
                    ->action(function (DoctorSchedule $record, array $data): void {
                        //you can use $record for fill appointment columns
                        Appointment::create([
                            'name' => $data['name'],
                            'gender' => $data['gender'],
                            'birthday' => $data['birthday'],
                            'phone_number' => $data['phone_number'],
                            'category' => $data['category'],
                            'specification' => $data['specification'],
                            // 'date' => TextColumn::get,
                            'status' => 'Pending',
                            'user_id' => auth()->user()->id,
                            'doctor_id' => $record->doctor_id,
                            'date' => $record->date,
                        ]);
                        // $appointment = $this->record;
                        Filament::notify(status: 'success', message: 'Appointment Successfully');
                        // $recipient = [auth()->user()->role_id==1,auth()->user()->role_id==$record->doctor_id];
                        $user = auth()->user();
                        Notification::make()
                            ->title('Appointment Created Successfully')
                            ->icon('heroicon-o-check-circle')
                            ->body("**Good day {$user}!, you have successfully book an appointment. You only need to show up on the scheduled date, thank you!.**")
                            ->iconColor('success')
                            ->sendToDatabase($user);

                        // Notification::make()
                        //     ->title('Appointment Created Successfully')
                        //     ->icon('heroicon-o-clipboard')
                        //     ->body("**{$recipient->name}, .**")
                        //     ->sendToDatabase($record->doctor_id);
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
                        Select::make('category')
                            ->options([
                                'Dental' => 'Dental',
                                'Check Up' => 'Check Up',
                                'Medical' => 'Medical',
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
                        // TextInput::make('doctor_id'),
                        // ->required(),
                        // DatePicker::make('date')
                        //     ->label('Appointment Date')
                        //     ->required(),
                    ])
                    ->modalHeading('Appointment')
                    ->modalSubheading('Fill up all the corresponding fields'),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')->hidden(auth()->user()->role_id == 4),
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
            'index' => Pages\ListDoctorSchedules::route('/'),
            'create' => Pages\CreateDoctorSchedule::route('/create'),
            'edit' => Pages\EditDoctorSchedule::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        $date = Carbon::now()->toDateString();
        if (auth()->user()->role_id == 4) {
            return parent::getEloquentQuery()
                ->whereDate('date', '>=', $date)

                ->count();
        } else {
            return self::getModel()::where('date', $date)->count();
        }
        // return self::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        // if role id of logged in user is 2, table must display all record with role id = 4
        // else, display all record
        $date = Carbon::now();
        if (auth()->user()->role_id == 4) {
            return parent::getEloquentQuery()
                ->whereDate('date', '>=', $date);
        } else {
            // code here
            return parent::getEloquentQuery();
        }
    }
}
