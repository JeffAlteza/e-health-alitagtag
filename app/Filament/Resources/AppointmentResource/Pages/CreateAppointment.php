<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Domain\Appointment\Actions\CreateAppointmentAction;
use Domain\Appointment\DataTransferObjects\AppointmentData;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(fn () => app(CreateAppointmentAction::class)->execute(new AppointmentData(...$data)));
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
