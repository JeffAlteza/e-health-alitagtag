<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\PatientRecord;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AppointmentOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    // protected static ?string $pollingInterval = '5s';
    protected function getCards(): array
    {
        $date = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->yesterday()->toDateString();

        $startWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY)->toDateString();
        $endWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

        $monday = Carbon::now()->now(Carbon::MONDAY)->toDateString();
        $tuesday = Carbon::now()->now(Carbon::TUESDAY)->toDateString();
        $wednesday = Carbon::now()->now(Carbon::WEDNESDAY)->toDateString();
        $thursday = Carbon::now()->now(Carbon::THURSDAY)->toDateString();
        $friday = Carbon::now()->now(Carbon::FRIDAY)->toDateString();

        $dateWeek = Carbon::now()->subDays(7);
        $datelastWeek = Carbon::now()->subDays(14);

        $countToday = Appointment::where('date', '=', $date)->count();
        $countYesterday = Appointment::where('date', '=', $yesterday)->count();

        $countTodayPatient = PatientRecord::where('date_of_consultation', '=', $date)->count();
        $countYesterdayPatient = PatientRecord::where('date_of_consultation', '=', $yesterday)->count();

        $countStartWeek = Appointment::where('date', '=', $startWeek)->count();
        $countMonday = Appointment::where('date', '=', $monday)->count();
        $countTuesday = Appointment::where('date', '=', $tuesday)->count();
        $countWednesday = Appointment::where('date', '=', $wednesday)->count();
        $countThursday = Appointment::where('date', '=', $thursday)->count();
        $countFriday = Appointment::where('date', '=', $friday)->count();
        $countEndWeek = Appointment::where('date', '=', $endWeek)->count();

        $pendingYesterday = Appointment::where('status', '=', 'pending')->where('date', '=', $date)->count();
        $pendingToday = Appointment::where('status', '=', 'pending')->where('date', '=', $yesterday)->count();

        $thisWeek = Appointment::where('date', '>', $dateWeek)->count();
        $lastWeek = Appointment::where('date', '>', $datelastWeek)->count();
        // $lastWeek = Appointment::all()
        // ->between(
        //     start: now()->startOfYear(),
        //     end: now()->endOfYear(),
        // )->count();
        return [
            Card::make('Success Consultation Today', PatientRecord::all()->where('date_of_consultation', $date)->count())
                ->color('primary')
                ->description($date)
                ->descriptionIcon('heroicon-s-clipboard-check')
                ->chart([
                    0, $countTodayPatient, 0,
                    $countYesterdayPatient,
                ]),
            Card::make('Appointments this Week', Appointment::all()->whereBetween('date', [$startWeek, $endWeek])->count())
                ->color('primary')
                ->description("{$startWeek} to {$endWeek}")
                ->descriptionIcon('heroicon-s-clipboard-list')
                ->chart([
                    0, $countStartWeek, 0,
                    // $countMonday,0,
                    // $countTuesday,0,
                    $countWednesday, 0,
                    // $countThursday,0,
                    // $countFriday,0,
                    $countEndWeek,
                ]),
            Card::make('Appointments Today', Appointment::where('date', '=', $date)->count())
                ->color('primary')
                ->description($date)
                ->descriptionIcon('heroicon-s-clipboard-copy')
                ->chart([0, $countYesterday, 0, $countToday]),
            Card::make('Pending Appointments Today', Appointment::where('status', '=', 'pending')->where('date', '=', $date)->count())
                ->color('warning')
                ->description($date)
                ->descriptionIcon('heroicon-s-clipboard-copy')
                ->chart([0, $pendingYesterday, 0, $pendingToday]),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role_id != 4;
    }
}
