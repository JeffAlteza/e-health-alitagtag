<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyAppointmentResource\Pages;
use App\Filament\Resources\MyAppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\MyAppointment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MyAppointmentResource extends Resource
{
    use HandlesAuthorization;

    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'my-appointment';

    protected static ?string $title = 'Appointment History';
 
    protected static ?string $navigationLabel = 'My Appointment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('doctor.id')
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
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('user.id')->sortable()->searchable()->toggleable(),
                TextColumn::make('name')->sortable()->searchable()->toggleable(),
                TextColumn::make('gender')->toggleable(),
                TextColumn::make('category')->toggleable(),
                TextColumn::make('specification')->toggleable(),
                TextColumn::make('doctor.name')->sortable()->searchable()->toggleable(),
                TextColumn::make('date')
                    ->date(),
                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                        'success' => 'success',
                    ])->sortable()->searchable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMyAppointments::route('/'),
        ];
    }    

    public static function canView(Model $record): bool
    {
        return false;
    }
        
    

    public static function CanViewAny(): bool
    {
        return false;
    }
}
