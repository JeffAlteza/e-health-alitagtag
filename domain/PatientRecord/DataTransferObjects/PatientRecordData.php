<?php

declare(strict_types=1);

namespace Domain\PatientRecord\DataTransferObjects;

class PatientRecordData
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string  $address,
        public readonly int  $age,
        public readonly string  $patient_number,
        public readonly ?string  $philhealth_id,
        public readonly string  $birthday,
        public readonly string  $phone_number,
        public readonly ?string  $date_of_consultation,
        public readonly ?string  $time_of_consultation,
        public readonly ?string  $nature_of_visit,
        public readonly ?string  $purpose_of_visit,
        public readonly ?string  $BP,
        public readonly ?string  $RR,
        public readonly ?string  $PR,
        public readonly ?string  $HC,
        public readonly ?string  $AC,
        public readonly ?string  $temp,
        public readonly ?string  $height,
        public readonly string  $weight,
        public readonly ?string  $LMP,
        public readonly ?string  $FHT,
        public readonly ?string  $EDC,
        public readonly ?string  $AOG,
        public readonly ?string  $FUNDIC_HT,
        public readonly ?string  $WAIST_CIR,
        public readonly ?bool  $smoker,
        public readonly ?bool  $alcohol_drinker,
        public readonly string  $chief_complaint,
        public readonly string  $recommendation,
    ) {
    }
}
