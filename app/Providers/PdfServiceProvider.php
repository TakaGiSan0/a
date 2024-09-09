<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;


class PdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('dompdf', function ($app) {
            return $app->make('dompdf.wrapper');
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
