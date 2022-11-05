<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestAppointment extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string |array $columnSpan = 'full'; 

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'date';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }

    protected function getTableQuery(): Builder
    {
        $dateWeek = Carbon::now()->subDays(7);
        return Appointment::where('date', '>', $dateWeek); 
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('date')->label('Appointment Date')->date(),
            TextColumn::make('name')->sortable()->searchable(),
            // TextColumn::make('gender'),
            TextColumn::make('category')->sortable()->searchable(),
            // TextColumn::make('specification'),
            TextColumn::make('doctor.name')->label('Doctor'),
            BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Cancelled',
                        'warning' => 'Pending',
                        'success' => 'Success',
                    ])->sortable()->searchable()
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->role_id != 4;
    }
}
