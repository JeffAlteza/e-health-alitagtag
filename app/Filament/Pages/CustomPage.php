<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CustomPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.custom-page';

    protected static ?string $navigationGroup = 'Custom';

    protected static ?int $navigationSort = 5;


}
