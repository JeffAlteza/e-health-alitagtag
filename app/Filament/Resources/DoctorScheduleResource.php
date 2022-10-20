<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorScheduleResource\Pages;
use App\Filament\Resources\DoctorScheduleResource\RelationManagers;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Savannabits\Flatpickr\Flatpickr;

class DoctorScheduleResource extends Resource
{
    protected static ?string $model = DoctorSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = "Manage";

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('doctor_id')
                    ->options(User::all()->where('role_id', '3')->pluck('name', 'id'))
                    ->label('Doctor Name')
                    ->required(),

                Select::make('category')
                    ->options([
                        'Dental' => 'Dental',
                        'Check Up' => 'Check Up',
                        'Medical' => 'Medical',
                        'Other' => 'Other',
                    ])->required(),

                DatePicker::make('date')
                    ->default(now())
                    ->required(),

                // Flatpickr::make('read_at')->default(now())->enableTime(),
                TimePicker::make('time_start')
                    ->withoutSeconds()
                    ->required(),

                TimePicker::make('time_end')
                    ->withoutSeconds()
                    ->required(),

                Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'unavailable' => 'Unavailable',
                    ])->required(),
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
                        'success' => 'available',
                    ])
            ])
            ->filters([
                Filter::make('schedule_at')
                    ->form([
                        DatePicker::make('schedule_from'),
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
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('book')
                    ->action(function (Appointment $record, array $data): void {
                        $record->create([
                            'name' => $data['name'],
                            'gender' => $data['gender'],
                            'birthday' => $data['birthday'],
                            'phone_number' => $data['phone_number'],
                            'category' => $data['category'],
                            'specification' => $data['specification'],
                            // 'date' => TextColumn::get,
                            'status' => 'pending',
                            'user_id' => auth()->user()->id,
                            // 'doctor_id' => $data['doctor_id'],
                        ]);
                        Filament::notify(status: 'success', message: 'Appointment Successfully');
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
                    ]),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
        return self::getModel()::count();
    }
}
