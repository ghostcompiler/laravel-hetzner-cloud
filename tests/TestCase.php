<?php

namespace Vendor\HetznerCloud\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vendor\HetznerCloud\Facades\Hetzner;
use Vendor\HetznerCloud\Providers\HetznerCloudServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            HetznerCloudServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Hetzner' => Hetzner::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default config
        $app['config']->set('hetzner-cloud.token', 'test-token');
        $app['config']->set('hetzner-cloud.base_url', 'https://api.hetzner.cloud/v1');
    }
}
