<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Tables\Columns\Column;
use Illuminate\Foundation\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css')
            );
        });

        Column::configureUsing(function (Column $column): void {
            $column
                ->searchable()
                ->toggleable()
                ->sortable();
        });
    }
}
