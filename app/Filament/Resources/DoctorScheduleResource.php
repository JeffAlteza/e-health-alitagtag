<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorScheduleResource\Pages;
use App\Filament\Resources\DoctorScheduleResource\RelationManagers;
use App\Models\DoctorSchedule;
use App\Models\User;
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
                ->options(User::all()->where('role_id', '3')->pluck('name','id'))
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
                TextColumn::make('doctor.name')->sortable()->toggleable()->searchable(),
                TextColumn::make('category')->toggleable()->searchable(),
                TextColumn::make('date')->sortable()->date()->toggleable()->searchable(),
                TextColumn::make('time_start')->toggleable()->searchable(),
                TextColumn::make('time_end')->toggleable()->searchable(),
                BadgeColumn::make('status')
                ->colors([
                    'danger' => 'unavailable',
                    'success' => 'available',
                ])->sortable()->toggleable()->searchable()
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
                Action::make('book'),
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
