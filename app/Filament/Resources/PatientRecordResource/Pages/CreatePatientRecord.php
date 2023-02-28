<?php

namespace App\Filament\Resources\PatientRecordResource\Pages;

use App\Filament\Resources\PatientRecordResource;
use Domain\PatientRecord\Actions\CreatePatientRecordAction;
use Domain\PatientRecord\DataTransferObjects\PatientRecordData;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreatePatientRecord extends CreateRecord
{
    protected static string $resource = PatientRecordResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(fn () => app(CreatePatientRecordAction::class)->execute(new PatientRecordData(...$data)));
    }
}
