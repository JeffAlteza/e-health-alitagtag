<?php

namespace App\Filament\Resources\DoctorScheduleResource\Pages;

use App\Filament\Resources\DoctorScheduleResource;
use Domain\Doctor\Actions\UpdateDoctorScheduleAction;
use Domain\Doctor\DataTransferObjects\DoctorScheduleData;
use Domain\Doctor\Models\DoctorSchedule;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditDoctorSchedule extends EditRecord
{
    protected static string $resource = DoctorScheduleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

     /** @param DoctorSchedule $record
     * @throws Throwable
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(fn () => app(UpdateDoctorScheduleAction::class)->execute($record, new DoctorScheduleData(...$data)));
    }
}
