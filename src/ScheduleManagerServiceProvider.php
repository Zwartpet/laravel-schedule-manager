<?php

namespace Zwartpet\ScheduleManager;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Zwartpet\ScheduleManager\Commands\ScheduleOptimize;
use Zwartpet\ScheduleManager\Commands\SchedulePause;
use Zwartpet\ScheduleManager\Commands\SchedulePaused;
use Zwartpet\ScheduleManager\Commands\ScheduleResume;

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
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'schedule-manager');
        if (config('schedule-manager.driver') === 'database') {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->optimizes(
                optimize: 'schedule:optimize',
            );

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('schedule-manager.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/schedule-manager'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/schedule-manager'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/schedule-manager'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                ScheduleOptimize::class,
                SchedulePause::class,
                SchedulePaused::class,
                ScheduleResume::class,
            ]);

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
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'schedule-manager');

        // Register the main class to use with the facade
        $this->app->singleton('schedule-manager', function (Application $app) {
            return new ScheduleManager;
        });
    }
}
