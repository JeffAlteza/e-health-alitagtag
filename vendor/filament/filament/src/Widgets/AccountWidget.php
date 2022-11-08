<?php

namespace Filament\Widgets;

class AccountWidget extends Widget
{
    protected static ?int $sort = -3;

    protected static string $view = 'filament::widgets.account-widget';

    protected int | string |array $columnSpan = 'full'; 

    public static function canView(): bool
    {
        return auth()->user()->role_id == 4;
    }
}
