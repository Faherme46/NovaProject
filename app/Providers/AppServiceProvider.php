<?php

namespace App\Providers;

use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\sessionController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use Maatwebsite\Excel\ExcelServiceProvider;
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
            $asambleaController=new AsambleaController();
            $sessionController=new sessionController();
            $id=$sessionController->getSessionId();
            $view->with('name_asamblea', $asambleaController->getName($id))->with('asambleaOn',$asambleaController->getOne($id));
        });
    }
}
