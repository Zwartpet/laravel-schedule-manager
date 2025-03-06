<?php

namespace Zwartpet\ScheduleManager\Drivers;

use Illuminate\Console\Scheduling\Event;
use Zwartpet\ScheduleManager\DTO\Pause;

interface DriverInterface
{
    public function shouldRunEvent(Event|string $event): bool;

    public function pauseEvent(Event|string $event, Pause $pause): void;

    public function resumeEvent(Event|string $event): void;

    public function getPause(Event|string $event): ?Pause;
}
