<?php

namespace App\Providers;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\sessionController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\ExcelServiceProvider;

use App\Models\State;

use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */


         public function register(): void
    {
        $this->app->register(ExcelServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {



        View::composer('*', function ($view) {
            

            
            $view->with(
                [
                    'asamblea' => cache('asamblea'),
                    'registro' => cache('inRegistro'),
                    'themeId'=> cache('themeId',5)
                ]
            );
        });
    }
}
