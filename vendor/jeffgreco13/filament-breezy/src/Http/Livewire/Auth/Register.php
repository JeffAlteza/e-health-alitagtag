<?php

namespace JeffGreco13\FilamentBreezy\Http\Livewire\Auth;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use JeffGreco13\FilamentBreezy\FilamentBreezy;
use Livewire\Component;

class Register extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $name;
    public $email;
    public $address;
    public $password;
    public $password_confirm;

    public function mount()
    {
        if (Filament::auth()->check()) {
            return redirect(config("filament.home_url"));
        }
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('filament-breezy::default.registration.notification_unique'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label(__('filament-breezy::default.fields.name'))
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(table: config('filament-breezy.user_model')),
            Forms\Components\TextInput::make('password')
                ->label(__('filament-breezy::default.fields.password'))
                ->required()
                ->password()
                ->rules(app(FilamentBreezy::class)->getPasswordRules()),
            Forms\Components\TextInput::make('password_confirm')
                ->label(__('filament-breezy::default.fields.password_confirm'))
                ->required()
                ->password()
                ->same('password'),
            Select::make('address')
                ->options([
                    'Balagbag' => 'Balagbag',
                    'Concepcion' => 'Concepcion',
                    'Concordia' => 'Concordia',
                    'Dalipit East' => 'Dalipit East',
                    'Dalipit West' => 'Dalipit West',
                    'Dominador East' => 'Dominador East',
                    'Dominador West' => 'Dominador West',
                    'Munlawin' => 'Munlawin',
                    'Muzon Primero' => 'Muzon Primero',
                    'Muzon Segundo' => 'Muzon Segundo',
                    'Pinagkurusan' => 'Pinagkurusan',
                    'Ping-As' => 'Ping-As',
                    'Poblacion East' => 'Poblacion East',
                    'Poblacion West' => 'Poblacion West',
                    'Salvador Agito' => 'Salvador Agito',
                    'San Jose' => 'San Jose',
                    'San Juan' => 'San Juan',
                    'Santa Cruz' => 'Santa Cruz',
                    'Tadlac' => 'Tadlac',

                ])
                ->required(),
        ];
    }

    protected function prepareModelData($data): array
    {
        $preparedData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
        ];

        return $preparedData;
    }

    public function register()
    {
        $preparedData = $this->prepareModelData($this->form->getState());

        $user = config('filament-breezy.user_model')::create($preparedData);

        event(new Registered($user));
        Filament::auth()->login($user, true);

        return redirect()->to(config('filament-breezy.registration_redirect_url'));
    }

    public function render(): View
    {
        $view = view('filament-breezy::register');

        $view->layout('filament::components.layouts.base', [
            'title' => __('filament-breezy::default.registration.title'),
        ]);

        return $view;
    }
}
