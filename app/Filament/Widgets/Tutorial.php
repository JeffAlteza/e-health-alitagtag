<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Widgets\Widget;

class Tutorial extends Widget
{
    protected static string $view = 'filament.widgets.tutorial';
    protected int | string |array $columnSpan = 'full'; 

    protected function getFormSchema():array
    {
        return [
            
            Wizard::make([  
                Step::make('First')->schema([
                    Card::make()->schema([
                        Textarea::make('Enter Message')
                    ])
                ]),
                Step::make('Second')->schema([

                ]),
                Step::make('Third')->schema([

                ]),
                Step::make('Fourth')->schema([

                ]),
            ])
        ];
    }
    public static function canView(): bool
    {
        return false;
        // return auth()->user()->role_id == 4;
    }
}
