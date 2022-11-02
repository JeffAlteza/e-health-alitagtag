<?php

namespace App\Filament\Resources\PatientRecordResource\Pages;

use App\Filament\Resources\PatientRecordResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatientRecord extends EditRecord
{
    protected static string $resource = PatientRecordResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
