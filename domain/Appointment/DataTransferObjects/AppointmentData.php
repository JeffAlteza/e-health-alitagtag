<?php

declare(strict_types=1);

namespace Domain\Appointment\DataTransferObjects;

class AppointmentData
{
    public function __construct(
        public readonly string $queue_number,
        public readonly string $name,
        public readonly string $gender,
        public readonly string $birthday,
        public readonly string $phone_number,
        public readonly string $category,
        public readonly string $specification,
        public readonly string $date,
        public readonly string $status,
        public readonly int $user_id,
        public readonly int $doctor_id,
        public readonly string $time,
        public readonly ?string $cancelation_reason,
    ) {
    }
}
