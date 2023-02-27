<?php

declare(strict_types=1);

namespace Domain\Doctor\Actions;

use Domain\Doctor\DataTransferObjects\DoctorScheduleData;
use Domain\Doctor\Models\DoctorSchedule;

class UpdateDoctorScheduleAction
{
    public function execute(DoctorSchedule $doctorSchedule, DoctorScheduleData $doctorData): DoctorSchedule
    {
        $doctorSchedule->update($this->getDoctorAttributes($doctorData));

        return $doctorSchedule;
    }

    protected function getDoctorAttributes(DoctorScheduleData $doctorData): array
    {
        return array_filter(
            [
                'category' => $doctorData->category,
                'date' => $doctorData->date,
                'time_start' => $doctorData->time_start,
                'time_end' => $doctorData->time_end,
                'status' => $doctorData->status,
                'doctor_id' => $doctorData->doctor_id,
            ],
            fn ($value) => filled($value)
        );
    }
}