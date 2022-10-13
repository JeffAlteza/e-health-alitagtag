<?php

namespace App\Filament\Resources\MyAppointmentResource\Pages;

use App\Filament\Resources\MyAppointmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMyAppointments extends ManageRecords
{
    protected static string $resource = MyAppointmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
