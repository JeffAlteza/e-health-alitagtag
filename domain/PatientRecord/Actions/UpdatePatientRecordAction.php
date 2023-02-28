<?php

declare(strict_types=1);

namespace Domain\PatientRecord\Actions;

use Domain\PatientRecord\DataTransferObjects\PatientRecordData;
use Domain\PatientRecord\Models\PatientRecord;

class UpdatePatientRecordAction
{
    public function execute(PatientRecord $patientRecord, PatientRecordData $patientRecordData): PatientRecord
    {
        $patientRecord->update($this->getPatientRecordAttributes($patientRecordData));

        return $patientRecord;
    }

    protected function getPatientRecordAttributes(PatientRecordData $patientRecordData): array
    {
        return array_filter(
            [
                    'name' => $patientRecordData->name,
                    'address' => $patientRecordData->address,
                    'age' => $patientRecordData->age,
                    'patient_number' => $patientRecordData->patient_number,
                    'philhealth_id' => $patientRecordData->philhealth_id,
                    'birthday' => $patientRecordData->birthday,
                    'phone_number' => $patientRecordData->phone_number,
                    'date_of_consultation' => $patientRecordData->date_of_consultation,
                    'time_of_consultation' => $patientRecordData->time_of_consultation,
                    'nature_of_visit' => $patientRecordData->nature_of_visit,
                    'purpose_of_visit' => $patientRecordData->purpose_of_visit,
                    'BP' => $patientRecordData->BP,
                    'RR' => $patientRecordData->RR,
                    'PR' => $patientRecordData->PR,
                    'HC' => $patientRecordData->HC,
                    'AC' => $patientRecordData->AC,
                    'temp' => $patientRecordData->temp,
                    'height' => $patientRecordData->height,
                    'weight' => $patientRecordData->weight,
                    'LMP' => $patientRecordData->LMP,
                    'FHT' => $patientRecordData->FHT,
                    'EDC' => $patientRecordData->EDC,
                    'AOG' => $patientRecordData->AOG,
                    'FUNDIC_HT' => $patientRecordData->FUNDIC_HT,
                    'WAIST_CIR' => $patientRecordData->WAIST_CIR,
                    'smoker' => $patientRecordData->smoker,
                    'alcohol_drinker' => $patientRecordData->alcohol_drinker,
                    'chief_complaint' => $patientRecordData->chief_complaint,
                    'recommendation' => $patientRecordData->recommendation,
            ],
            fn ($value) => filled($value)
        );
    }
}
