<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DoctorAvailable extends BaseWidget
{
    protected function getCards(): array
    {
        $date = Carbon::now()->toDateString();

        return [
            //
            Card::make('Available Schedule Today', DoctorSchedule::all()->where('date', $date)->count())
                ->color('primary')
                ->description($date)
                ->descriptionIcon('heroicon-s-clipboard-check'),
            Card::make('My Total Appointment', Appointment::all()->where('user_id', auth()->user()->id)->count())
                ->color('primary')
                ->description("As of {$date}")
                ->descriptionIcon('heroicon-s-clipboard-check'),
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->isPatient();
    }
}
