<?php

namespace App\Filament\Resources\PatientRecordResource\Pages;

use App\Filament\Resources\PatientRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientRecord extends CreateRecord
{
    protected static string $resource = PatientRecordResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
