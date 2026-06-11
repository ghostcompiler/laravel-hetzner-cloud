<?php

namespace Vendor\HetznerCloud\Providers;

use Illuminate\Support\ServiceProvider;
use Vendor\HetznerCloud\Http\Client\HetznerClient;
use Vendor\HetznerCloud\Managers\HetznerManager;

class HetznerCloudServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/hetzner-cloud.php',
            'hetzner-cloud'
        );

        // Bind HetznerClient
        $this->app->singleton(HetznerClient::class, function ($app) {
            $config = $app['config']->get('hetzner-cloud', []);

            return new HetznerClient($config['token'] ?? '', $config);
        });

        // Bind HetznerManager
        $this->app->singleton(HetznerManager::class, function ($app) {
            return new HetznerManager($app->make(HetznerClient::class));
        });

        // Register aliases
        $this->app->alias(HetznerManager::class, 'hetzner');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/hetzner-cloud.php' => config_path('hetzner-cloud.php'),
            ], 'config');
        }
    }
}
