<?php

namespace Zwartpet\ScheduleManager;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Zwartpet\ScheduleManager\Commands\ScheduleOptimize;
use Zwartpet\ScheduleManager\Commands\SchedulePause;
use Zwartpet\ScheduleManager\Commands\SchedulePaused;
use Zwartpet\ScheduleManager\Commands\ScheduleResume;
use Zwartpet\ScheduleManager\Livewire\ScheduleManagerComponent;

class ScheduleManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Schedule $schedule, ScheduleManager $scheduleManager): void
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'schedule-manager');

        if (config('schedule-manager.ui.enabled')) {
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'schedule-manager');
            $this->loadRoutesFrom(__DIR__.'/routes.php');

            Livewire::component('schedule-manager-component', ScheduleManagerComponent::class);
        }

        if (config('schedule-manager.driver') === 'database') {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->commands([
            ScheduleOptimize::class,
            SchedulePause::class,
            SchedulePaused::class,
            ScheduleResume::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->optimizes(
                optimize: 'schedule:optimize',
            );

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('schedule-manager.php'),
            ], ['config', 'schedule-manager-config']);

            $this->publishes([
                __DIR__.'/../public/vendor/schedule-manager/assets' => public_path('vendor/schedule-manager/assets'),
                __DIR__.'/../public/vendor/schedule-manager/manifest.json' => public_path('vendor/schedule-manager/manifest.json'),
            ], ['assets', 'schedule-manager-assets']);

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/schedule-manager'),
            ], 'lang');*/

            foreach ($schedule->events() as $event) {
                $event->when(function () use ($event, $scheduleManager) {
                    return $scheduleManager->shouldRunEvent($event);
                });
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'schedule-manager');

        $this->app->singleton('schedule-manager', function (Application $app) {
            return new ScheduleManager;
        });
    }
}
