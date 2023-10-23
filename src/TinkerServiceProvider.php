<?php

namespace Harmony\Tinker;

use G4T\Contracts\Support\DeferrableProvider;
use G4T\Foundation\Application as HarmonyApplication;
use G4T\Support\ServiceProvider;
use Harmony\Lumen\Application as LumenApplication;
use Harmony\Tinker\Console\TinkerCommand;

class TinkerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath($raw = __DIR__.'/../config/tinker.php') ?: $raw;

        if ($this->app instanceof HarmonyApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => $this->app->configPath('tinker.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('tinker');
        }

        $this->mergeConfigFrom($source, 'tinker');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.tinker', function () {
            return new TinkerCommand;
        });

        $this->commands(['command.tinker']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.tinker'];
    }
}
