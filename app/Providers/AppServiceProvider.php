<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Microsoft\MicrosoftExtendSocialite;

use Illuminate\Http\Request;
use App\Contracts\DatabaseServiceInterface;
use App\Services\ProdDatabaseService;
use App\Services\DevDatabaseService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // El método bind se utilizará para cada llamada al servicio
        $this->app->bind(DatabaseServiceInterface::class, function ($app) {
            
            $env = session('env', '0'); 

            //dd($env);
            if ($env === '1') {
                return new ProdDatabaseService();
            } else {
                return new DevDatabaseService();
            }
        });
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['events']->listen(
            SocialiteWasCalled::class,
            MicrosoftExtendSocialite::class.'@handle'
            );
    }
}
