<?php

declare(strict_types=1);

namespace Domain\Doctor\DataTransferObjects;

class DoctorScheduleData
{
    public function __construct(
        public readonly string $category,
        public readonly ?string $date,
        public readonly string $time_start,
        public readonly string $time_end,
        public readonly string $status,
        public readonly int $doctor_id,
    ) {
    }
}
