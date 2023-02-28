<?php

declare(strict_types=1);

namespace Domain\Appointment\Actions;

use Domain\Appointment\DataTransferObjects\AppointmentData;
use Domain\Appointment\Models\Appointment;

class CreateAppointmentAction
{
    public function execute(AppointmentData $appointmentData): Appointment
    {
        $appointment = Appointment::create($this->getAppointmentAttributes($appointmentData));

        return $appointment;
    }

    protected function getAppointmentAttributes(AppointmentData $appointmentData): array
    {
        return array_filter(
            [
                'queue_number' => $appointmentData->queue_number,
                'name' => $appointmentData->name,
                'gender' => $appointmentData->gender,
                'birthday' => $appointmentData->birthday,
                'phone_number' => $appointmentData->phone_number,
                'category' => $appointmentData->category,
                'specification' => $appointmentData->specification,
                'date' => $appointmentData->date,
                'status' => $appointmentData->status,
                'user_id' => $appointmentData->user_id,
                'doctor_id' => $appointmentData->doctor_id,
                'time' => $appointmentData->time,
                'cancelation_reason'=> $appointmentData->cancelation_reason,
            ],
            fn ($value) => filled($value)
        );
    }
}
