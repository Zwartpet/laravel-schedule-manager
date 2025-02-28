<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\ScheduleManager;

beforeEach(function () {});

it('can run a schedule', function () {
    Cache::spy();

    $schedule = $this->app->get(Schedule::class);

    /** @var Event $event */
    $event = $schedule->events()[0];

    Cache::shouldReceive('store')
        ->andReturnSelf();

    Cache::shouldReceive('has')
        ->once()
        ->with(config('schedule-manager.drivers.cache.prefix').$event->mutexName())
        ->andReturn(false);

    expect($event->filtersPass($this->app))->toBeTrue();
});

it('can pause a schedule', function () {
    Cache::spy();

    $schedule = $this->app->get(Schedule::class);
    $scheduleManager = $this->app->get(ScheduleManager::class);

    /** @var Event $event */
    $event = $schedule->events()[0];

    Cache::shouldReceive('store')
        ->andReturnSelf();

    Cache::shouldReceive('has')
        ->once()
        ->with(config('schedule-manager.drivers.cache.prefix').$event->mutexName())
        ->andReturn(true);

    Cache::shouldReceive('put')
        ->once();

    $scheduleManager->pauseEvent($event);

    expect($event->filtersPass($this->app))->toBeFalse();
});

it('can resume a schedule', function () {
    Cache::spy();

    $schedule = $this->app->get(Schedule::class);
    $scheduleManager = $this->app->get(ScheduleManager::class);

    /** @var Event $event */
    $event = $schedule->events()[0];

    Cache::shouldReceive('store')
        ->andReturnSelf();

    Cache::shouldReceive('forget')
        ->once();

    $scheduleManager->resumeEvent($event);
});
