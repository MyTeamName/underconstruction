<?php

namespace LarsJanssen\UnderConstruction;

use Illuminate\Support\ServiceProvider;

class UnderConstructionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/under-construction.php' => config_path('under-construction.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'views');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->commands('LarsJanssen\UnderConstruction\Commands\SetCodeCommand');
        $this->mergeConfigFrom(__DIR__ . '/../config/under-construction.php', 'under-construction');

        $routeConfig = [
            'namespace' => 'LarsJanssen\UnderConstruction\Controllers',
            'prefix' => 'under',
        //    'middleware' => [DebugbarEnabled::class],
        ];
        $this->getRouter()->group($routeConfig, function($router) {
            $router->post('check', [
                'uses' => 'CodeController@check',
                'as' => 'underconstruction.check',
            ]);

            $router->get('construction', [
                'uses' => 'CodeController@index',
                'as' => 'underconstruction.index',
            ]);

            $router->get('js', [
                'uses' => 'AssetController@js',
                'as' => 'underconstruction.js',
            ]);
        });
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }
}