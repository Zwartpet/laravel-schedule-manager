<?php

namespace Zwartpet\ScheduleManager\Drivers;

use Illuminate\Console\Scheduling\Event;
use Zwartpet\ScheduleManager\DTO\Pause;

interface DriverInterface
{
    public function shouldRunEvent(Event $event): bool;

    public function pauseEvent(Event $event, Pause $pause): void;

    public function resumeEvent(Event $event): void;

    public function getPause(Event $event): Pause;
}
