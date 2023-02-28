<?php

namespace App\Filament\Resources\PatientRecordResource\Pages;

use App\Filament\Resources\PatientRecordResource;
use Domain\PatientRecord\Actions\UpdatePatientRecordAction;
use Domain\PatientRecord\DataTransferObjects\PatientRecordData;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditPatientRecord extends EditRecord
{
    protected static string $resource = PatientRecordResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(fn () => app(UpdatePatientRecordAction::class)->execute($record, new PatientRecordData(...$data)));
    }
}
