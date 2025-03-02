<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\Models\PausedSchedule;
use Zwartpet\ScheduleManager\ScheduleManager;

beforeEach(function () {
    config(['schedule-manager.driver' => 'database']);
});

it('can run a schedule', function () {
    $schedule = $this->app->get(Schedule::class);

    /** @var Event $event */
    $event = $schedule->events()[0];

    expect($event->filtersPass($this->app))->toBeTrue();
});

it('can pause a schedule', function () {
    $schedule = $this->app->get(Schedule::class);
    $scheduleManager = $this->app->get(ScheduleManager::class);

    /** @var Event $event */
    $event = $schedule->events()[0];

    $scheduleManager->pauseEvent($event);

    expect($event->filtersPass($this->app))->toBeFalse();
    expect(PausedSchedule::count())->toBe(1);
});

it('can resume a schedule', function () {
    $schedule = $this->app->get(Schedule::class);
    $scheduleManager = $this->app->get(ScheduleManager::class);

    /** @var Event $event */
    $event = $schedule->events()[0];

    PausedSchedule::create([
        'mutex_name' => $event->mutexName(),
        'pause' => json_encode(['pauseUntil' => now()->addMinutes(5)]),
        'pause_until' => now()->addMinutes(5),
    ]);

    $scheduleManager->resumeEvent($event);

    expect(PausedSchedule::count())->toBe(0);
});
