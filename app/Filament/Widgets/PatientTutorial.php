<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;

class PatientTutorial extends Widget
{
    protected static string $view = 'filament.widgets.patient-tutorial';

    protected function getCards(): array
    {
        return [
            Card::make('Success Consultation Today', 2)
                ->color('primary'),
        ];
    }

    public static function canView(): bool
    {
        return false;
    }
}
