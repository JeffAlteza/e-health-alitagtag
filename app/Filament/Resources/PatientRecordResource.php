<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientRecordResource\Pages;
use App\Filament\Resources\PatientRecordResource\RelationManagers;
use App\Models\PatientRecord;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;

class PatientRecordResource extends Resource
{
    protected static ?string $model = PatientRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = "Manage";

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Personal Information')->schema([
                        Card::make()->schema([
                            Fieldset::make('Information')
                                ->schema([
                                    TextInput::make('patient_number')
                                        ->required(),
                                    TextInput::make('philhealth_id'),
                                    TextInput::make('name')
                                        ->required(),
                                    TextInput::make('age')
                                        ->numeric()
                                        ->required(),
                                    DatePicker::make('birthday')
                                        ->required(),
                                    TextInput::make('phone_number')
                                        ->required(),
                                    TextInput::make('address')
                                        ->columnSpan('full'),
                                    TextInput::make('height')->numeric()->label('Height(cm)'),
                                    TextInput::make('weight')->numeric()->label('Weight(kg)')->required(),
                                ]),
                            Fieldset::make('Consultation')
                                ->schema([
                                    DatePicker::make('date_of_consultation')
                                        ->default(now()),
                                    TextInput::make('time_of_consultation'),
                                    TextInput::make('nature_of_visit'),
                                    TextInput::make('purpose_of_visit'),
                                    Radio::make('smoker')
                                        ->label('Smoker?')
                                        ->boolean()
                                        ->inline(),
                                    Radio::make('alcohol_drinker')
                                        ->label('Alcohol Drinker?')
                                        ->boolean()
                                        ->inline(),
                                ]),

                        ])->columns(2)
                    ]),
                    Step::make('Vital Signs')->schema([
                        Card::make()->schema([

                            TextInput::make('BP')->label('BP'),
                            TextInput::make('RR')->label('RR'),
                            TextInput::make('PR')->label('PR'),
                            TextInput::make('HC')->label('HC(cm)'),
                            TextInput::make('AC')->label('AC(cm)'),
                            TextInput::make('temp')->label('Temperature (°C)'),
                            TextInput::make('LMP')->label('LMP'),
                            TextInput::make('FHT')->label('FHT (bpm)'),
                            TextInput::make('EDC')->label('EDC'),
                            TextInput::make('AOG')->label('AOG'),
                            TextInput::make('FUNDIC_HT')->label('FUNDIC HT (cm)'),
                            TextInput::make('WAIST_CIR')->label('WAIST CIR'),
                        ])->columns(3)
                    ]),
                    Step::make('Recommendations')->schema([
                        Card::make()->schema([
                            Textarea::make('chief_complaint')->required(),
                            Textarea::make('recommendation')
                                ->label('Treatment/Management/Medicine Given:')
                                ->required(),
                        ])
                    ])->columns(1)
                ])->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_number'),
                TextColumn::make('name'),
                TextColumn::make('age'),
                TextColumn::make('date_of_consultation')->date(),
            ])
            ->defaultSort('date_of_consultation', 'desc')
            ->filters([
                Filter::make('date_of_consultation')
                    ->form([
                        DatePicker::make('consultation_from'),
                        DatePicker::make('consultation_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['consultation_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_consultation', '>=', $date),
                            )
                            ->when(
                                $data['consultation_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_consultation', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('warning'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export'),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')->hidden(auth()->user()->role_id == 4)
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
            'index' => Pages\ListPatientRecords::route('/'),
            'create' => Pages\CreatePatientRecord::route('/create'),
            'edit' => Pages\EditPatientRecord::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        // dd(self::getModel()::where('date_of_consultation',now())->count());
        $date = Carbon::now()->toDateString();
        return PatientRecord::all()->where('date_of_consultation', $date)->count();
    }
}
