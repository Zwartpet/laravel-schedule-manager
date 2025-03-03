<?php

namespace Zwartpet\ScheduleManager;

use DateTimeInterface;
use Illuminate\Console\Scheduling\Event;
use Zwartpet\ScheduleManager\Drivers\DriverFactory;
use Zwartpet\ScheduleManager\Drivers\DriverInterface;
use Zwartpet\ScheduleManager\DTO\Pause;

class ScheduleManager
{
    public function shouldRunEvent(Event $event): bool
    {
        return $this->getDriver()->shouldRunEvent($event);
    }

    public function pauseEvent(Event $event, ?string $description = null, ?DateTimeInterface $pauseUntil = null): void
    {
        $this->getDriver()->pauseEvent($event, new Pause($description, $pauseUntil));
    }

    public function resumeEvent(Event $event): void
    {
        $this->getDriver()->resumeEvent($event);
    }

    public function getPause(Event $event): ?Pause
    {
        return $this->getDriver()->getPause($event);
    }

    private function getDriver(): DriverInterface
    {
        return DriverFactory::create(config('schedule-manager.driver'));
    }
}
