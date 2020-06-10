<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\Schema; //for fix problem with strings too long in DB
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Schema::defaultStringLength(191); //for fix problem with strings too long in DB
        //Resource::withoutWrapping();//Objekty nieotoczone selektorem "data"
    }
}
