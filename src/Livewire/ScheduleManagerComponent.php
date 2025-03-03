<?php

namespace Zwartpet\ScheduleManager\Livewire;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Zwartpet\ScheduleManager\ScheduleManager;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

class ScheduleManagerComponent extends Component
{
    use EventPrintable;

    public ?string $pausing = null;

    public ?string $pausingCommand = null;

    public $description = '';

    public $pauseUntil = '';

    public function startPausing(string $mutexName, Schedule $schedule)
    {
        $this->pausing = $mutexName;

        $event = collect($schedule->events())
            ->first(fn (Event $event) => $event->mutexName() === $this->pausing);

        $this->pausingCommand = $this->normalizeEvent($event);
    }

    public function pause(Schedule $schedule, ScheduleManager $scheduleManager)
    {
        $event = collect($schedule->events())
            ->first(fn (Event $event) => $event->mutexName() === $this->pausing);

        $scheduleManager->pauseEvent(
            $event,
            $this->description,
            $this->pauseUntil ? Carbon::parse($this->pauseUntil) : null,
        );

        $this->pausing = null;
        $this->pausingCommand = null;
    }

    public function resume(string $mutexName, Schedule $schedule, ScheduleManager $scheduleManager)
    {
        $event = collect($schedule->events())
            ->first(fn (Event $event) => $event->mutexName() === $mutexName);

        $scheduleManager->resumeEvent($event);
    }

    #[Layout('schedule-manager::components.layouts.app')]
    public function render(Schedule $schedule, ScheduleManager $scheduleManager)
    {
        $schedules = collect($schedule->events())
            ->map(fn (Event $event) => [
                'command' => $this->normalizeEvent($event),
                'is_paused' => ! $scheduleManager->shouldRunEvent($event),
                'pause_until' => $scheduleManager->getPause($event)?->pauseUntil,
                'mutex_name' => $event->mutexName(),
            ])
            ->toArray();

        return view('schedule-manager::livewire.schedule-manager', [
            'schedules' => $schedules,
        ]);
    }
}
