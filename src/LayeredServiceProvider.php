<?php

namespace SiDev\LaravelLayered;

use Illuminate\Support\ServiceProvider;
use SiDev\LaravelLayered\Console\Commands\ClassMakeCommand;
use SiDev\LaravelLayered\Console\Commands\ContractMakeCommand;
use SiDev\LaravelLayered\Console\Commands\LayeredBunchMakeCommand;
use SiDev\LaravelLayered\Console\Commands\RepositoryMakeCommand;
use SiDev\LaravelLayered\Console\Commands\ServiceMakeCommand;
use SiDev\LaravelLayered\Console\Commands\TraitMakeCommand;

class LayeredServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/layered.php', 'layered');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/layered.php' => config_path('layered.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClassMakeCommand::class,
                ContractMakeCommand::class,
                LayeredBunchMakeCommand::class,
                RepositoryMakeCommand::class,
                ServiceMakeCommand::class,
                TraitMakeCommand::class,
            ]);
        }
    }
}
