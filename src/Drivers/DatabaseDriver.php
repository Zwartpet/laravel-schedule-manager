<?php

namespace Zwartpet\ScheduleManager\Drivers;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Eloquent\Collection;
use Zwartpet\ScheduleManager\DTO\Pause;
use Zwartpet\ScheduleManager\Models\PausedSchedule;

class DatabaseDriver implements DriverInterface
{
    /**
     * @var Collection<int, PausedSchedule>|null
     */
    private ?Collection $pausedSchedules = null;

    public function shouldRunEvent(Event|string $event): bool
    {
        $pausedSchedule = $this->getPausedSchedule($event);
        if ($pausedSchedule === null) {
            return true;
        }

        return $pausedSchedule->pause_until !== null && $pausedSchedule->pause_until->isPast();
    }

    public function pauseEvent(Event|string $event, Pause $pause): void
    {
        PausedSchedule::updateOrCreate(
            ['mutex_name' => $this->getName($event)],
            [
                'pause' => json_encode($pause),
                'pause_until' => $pause->pauseUntil,
            ]
        );
    }

    public function resumeEvent(Event|string $event): void
    {
        $this->getPausedSchedule($event)?->delete();
    }

    public function getPause(Event|string $event): ?Pause
    {
        if ($this->getPausedSchedule($event) === null) {
            return null;
        }

        return Pause::fromArray(json_decode($this->getPausedSchedule($event)->pause, true));
    }

    private function getPausedSchedule(Event|string $event): ?PausedSchedule
    {
        $this->pausedSchedules ??= PausedSchedule::whereNull('pause_until')
            ->orWhereFuture('pause_until')
            ->get();

        if ($this->pausedSchedules->isEmpty()) {
            return null;
        }

        /** @var PausedSchedule|null */
        return $this->pausedSchedules->firstWhere('mutex_name', $this->getName($event));
    }

    private function getName(Event|string $event): string
    {
        return is_string($event) ? $event : $event->mutexName();
    }
}
