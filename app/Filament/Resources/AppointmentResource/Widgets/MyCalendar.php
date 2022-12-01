<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\Appointment;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Widgets\Widget;

class MyCalendar extends FullCalendarWidget
{
    // protected static string $view = 'filament.resources.appointment-resource.widgets.my-calendar';
    public function getViewData(): array
    {
        return Appointment::where('status', 'Approved')->where('doctor_id', auth()->user()->id)
            ->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'title' => $data->name,
                    'start' => $data->date,
                    // 'category' => $data->category,
                    // 'specification' => $data->specification,
                    // 'end' => $intervention['scheduled_end_datetime'],
                ];
            })->toArray();
    }

    public static function canView(?array $event = null): bool
    {
        return auth()->user()->role_id == 3;
    }
}
