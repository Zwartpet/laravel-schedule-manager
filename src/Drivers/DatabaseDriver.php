<?php

namespace Zwartpet\ScheduleManager\Drivers;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Eloquent\Collection;
use Zwartpet\ScheduleManager\DTO\Pause;
use Zwartpet\ScheduleManager\Models\PausedSchedule;

class DatabaseDriver implements DriverInterface
{
    /**
     * @var Collection<PausedSchedule>|null
     */
    private ?Collection $pausedSchedules = null;

    public function shouldRunEvent(Event $event): bool
    {
        $pausedSchedule = $this->getPausedSchedule($event);
        if ($pausedSchedule === null) {
            return true;
        }

        return $pausedSchedule->pause_until !== null && $pausedSchedule->pause_until->isPast();
    }

    public function pauseEvent(Event $event, Pause $pause): void
    {
        PausedSchedule::updateOrCreate(
            ['mutex_name' => $event->mutexName()],
            [
                'pause' => json_encode($pause),
                'pause_until' => $pause->pauseUntil,
            ]
        );
    }

    public function resumeEvent(Event $event): void
    {
        $this->getPausedSchedule($event)?->delete();
    }

    public function getPause(Event $event): Pause
    {
        return Pause::fromArray(json_decode($this->getPausedSchedule($event)->pause, true));
    }

    private function getPausedSchedule(Event $event): ?PausedSchedule
    {
        $this->pausedSchedules ??= PausedSchedule::whereNull('pause_until')
            ->orWhereFuture('pause_until')
            ->get();

        if ($this->pausedSchedules->isEmpty()) {
            return null;
        }

        /** @var PausedSchedule|null */
        return $this->pausedSchedules->firstWhere('mutex_name', $event->mutexName());
    }
}
