<?php

namespace App\Filament\Widgets;

use App\Models\PatientRecord;
use Filament\Widgets\BarChartWidget;

class SuccessConsultationChart extends BarChartWidget
{
    protected static ?string $heading = 'Consultation per Month';

    protected static ?int $sort = 3;

    // protected static ?string $minHeight = '300px';

    // protected static ?string $pollingInterval = '5s';

    protected function getData(): array
    {
        $january = PatientRecord::whereMonth('date_of_consultation', '1')->whereYear('date_of_consultation', now('Y'))->count();
        $february = PatientRecord::whereMonth('date_of_consultation', '2')->whereYear('date_of_consultation', now('Y'))->count();
        $march = PatientRecord::whereMonth('date_of_consultation', '3')->whereYear('date_of_consultation', now('Y'))->count();
        $april = PatientRecord::whereMonth('date_of_consultation', '4')->whereYear('date_of_consultation', now('Y'))->count();
        $may = PatientRecord::whereMonth('date_of_consultation', '5')->whereYear('date_of_consultation', now('Y'))->count();
        $june = PatientRecord::whereMonth('date_of_consultation', '6')->whereYear('date_of_consultation', now('Y'))->count();
        $july = PatientRecord::whereMonth('date_of_consultation', '7')->whereYear('date_of_consultation', now('Y'))->count();
        $august = PatientRecord::whereMonth('date_of_consultation', '8')->whereYear('date_of_consultation', now('Y'))->count();
        $september = PatientRecord::whereMonth('date_of_consultation', '9')->whereYear('date_of_consultation', now('Y'))->count();
        $october = PatientRecord::whereMonth('date_of_consultation', '10')->whereYear('date_of_consultation', now('Y'))->count();
        $november = PatientRecord::whereMonth('date_of_consultation', '11')->whereYear('date_of_consultation', now('Y'))->count();
        $december = PatientRecord::whereMonth('date_of_consultation', '12')->whereYear('date_of_consultation', now('Y'))->count();

        return [
            'datasets' => [
                [
                    'label' => 'Successful Consultation',
                    'data' => [
                        $january,
                        $february,
                        $march,
                        $april,
                        $may,
                        $june,
                        $july,
                        $august,
                        $september,
                        $october,
                        $november,
                        $december,
                    ],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role_id != 4;
    }
}
