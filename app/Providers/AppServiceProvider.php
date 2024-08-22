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
            $statesCollect = State::all();
            $states = [];
            foreach ($statesCollect as $item) {
                $states[$item->id] = $item->value;
                # code...
            }

            $asamblea = cache('asamblea',false);
            $view->with(
                [
                    'asamblea' => $asamblea,
                    'registro' => ($asamblea) ? $asamblea['registro'] : false,
                    'states' => $states
                ]
            );
        });
    }
}
