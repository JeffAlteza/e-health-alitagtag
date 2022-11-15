<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\BarChartWidget;

class SuccessAppointmentsChart extends BarChartWidget
{
    protected static ?string $heading = 'Appointments per Month';

    protected static ?int $sort = 2;

    // protected static ?string $minHeight = '300px';

    // protected static ?string $pollingInterval = '5s';

    protected function getData(): array
    {
        $january = Appointment::whereMonth('date', '1')->where('status', 'Success')->count();
        $february = Appointment::whereMonth('date', '2')->where('status', 'Success')->count();
        $march = Appointment::whereMonth('date', '3')->where('status', 'Success')->count();
        $april = Appointment::whereMonth('date', '4')->where('status', 'Success')->count();
        $may = Appointment::whereMonth('date', '5')->where('status', 'Success')->count();
        $june = Appointment::whereMonth('date', '6')->where('status', 'Success')->count();
        $july = Appointment::whereMonth('date', '7')->where('status', 'Success')->count();
        $august = Appointment::whereMonth('date', '8')->where('status', 'Success')->count();
        $september = Appointment::whereMonth('date', '9')->where('status', 'Success')->count();
        $october = Appointment::whereMonth('date', '10')->where('status', 'Success')->count();
        $november = Appointment::whereMonth('date', '11')->where('status', 'Success')->count();
        $december = Appointment::whereMonth('date', '12')->where('status', 'Success')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Successful Appointment',
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
        return false;
        // return auth()->user()->role_id != 4;
    }
}
