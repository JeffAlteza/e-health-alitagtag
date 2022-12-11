<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestAppointment extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string |array $columnSpan = 'full';

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'date';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableQuery(): Builder
    {
        $dateWeek = Carbon::now()->subDays(7);
        // return Appointment::where('date', '>', $dateWeek);
        return AppointmentResource::getEloquentQuery()->take(5);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
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
                        'success' => 'Approved',
                    ])->sortable()->searchable(),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role_id != 4;
    }
}
