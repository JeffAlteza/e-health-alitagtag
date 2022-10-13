<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as RulesPassword;



class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
             Action::make(name:'changePassword') 
             ->form([
                TextInput::make(name:'newPassword')
                    ->password()
                    ->label(label:'New Password')
                    ->required()
                    ->rule(RulesPassword::default()),
                TextInput::make(name:'newPasswordConfirmation')
                    ->password()
                    ->label(label:'Confirm  New Password')
                    ->required()
                    ->same(statePath:'newPassword')
                    ->rule(RulesPassword::default()),
             ])
            //  modal for changing password
             ->action(function (array $data){
                    $this->record->update([
                        'password'=>Hash::make($data['newPassword'])
                    ]);
                    $this->notify(status:'success', message:'Password updated Successfully'); 
             }) 
        ];
    }
}
