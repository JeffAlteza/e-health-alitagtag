<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AppointmentResource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AppointmentHistory extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string |array $columnSpan = 'full';

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'date';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableQuery(): Builder
    {
        return AppointmentResource::getEloquentQuery()->take(5)->where('user_id', auth()->user()->id);
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
                    ])->sortable()->searchable(),
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->isPatient();
    }
}
