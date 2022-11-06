<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\DoughnutChartWidget;

class SpecificationChart extends DoughnutChartWidget
{
    protected static ?string $heading = 'Specification per Month';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '275px';
    // protected static ?string $pollingInterval = '5s';
    protected function getData(): array
    {
        $child = Appointment::whereMonth('date', now())->where('specification', 'Child')->count();
        $teen = Appointment::whereMonth('date', now())->where('specification', 'Teen')->count();
        $adult = Appointment::whereMonth('date', now())->where('specification', 'Adult')->count();
        $senior = Appointment::whereMonth('date', now())->where('specification', 'Senior')->count();
        $infant = Appointment::whereMonth('date', now())->where('specification', 'Infant')->count();
        return [
            'datasets' => [
                [
                    'label' => 'Successful Appointment',
                    'data' => [
                        $infant,
                        $child,
                        $teen,
                        $adult,
                        $senior,
                    ],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Infant', 'Child', 'Teen', 'Adult', 'Senior'],
        ];
    }
    public static function canView(): bool
    {
        // return false;
        return auth()->user()->role_id != 4;
    }
}
