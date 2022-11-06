<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $appointment = $this->record;

        Notification::make()
            ->title('New Appointment')
            ->icon('heroicon-o-clipboard')
            ->body("**{$appointment->name} book an appointment at {$appointment->date}.**")
            ->actions([
                Action::make('View')
                    ->url(AppointmentResource::getUrl('edit', ['record' => $appointment])),
            ])
            ->sendToDatabase(auth()->user());
    }
}
