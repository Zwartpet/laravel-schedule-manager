<?php

namespace Zwartpet\ScheduleManager;

use DateTimeInterface;
use Illuminate\Console\Scheduling\Event;
use Zwartpet\ScheduleManager\Drivers\DriverFactory;
use Zwartpet\ScheduleManager\Drivers\DriverInterface;
use Zwartpet\ScheduleManager\DTO\Pause;

class ScheduleManager
{
    public function shouldRunEvent(Event|string $event): bool
    {
        return $this->getDriver()->shouldRunEvent($event);
    }

    public function pauseEvent(Event|string $event, ?string $description = null, ?DateTimeInterface $pauseUntil = null): void
    {
        $this->getDriver()->pauseEvent($event, new Pause($description, $pauseUntil));
    }

    public function resumeEvent(Event|string $event): void
    {
        $this->getDriver()->resumeEvent($event);
    }

    public function getPause(Event|string $event): ?Pause
    {
        return $this->getDriver()->getPause($event);
    }

    private function getDriver(): DriverInterface
    {
        return DriverFactory::create(config('schedule-manager.driver'));
    }
}
