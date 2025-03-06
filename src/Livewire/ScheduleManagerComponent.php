<?php

namespace Zwartpet\ScheduleManager\Livewire;

use Cache;
use Carbon\Carbon;
use Gate;
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

    public function __construct()
    {
        if (config('schedule-manager.ui.gate')) {
            if (! Gate::has(config('schedule-manager.ui.gate'))) {
                abort(404, 'Missing gate: schedule-manager');
            }

            if (! Gate::allows(config('schedule-manager.ui.gate'))) {
                abort(403);
            }
        }
    }

    public function startPausing(string $mutexName)
    {
        $this->pausing = $mutexName;

        $this->pausingCommand = data_get($this->getEvent($mutexName), 'command');
    }

    public function pause(ScheduleManager $scheduleManager)
    {
        $scheduleManager->pauseEvent(
            $this->pausing,
            $this->description,
            $this->pauseUntil ? Carbon::parse($this->pauseUntil) : null,
        );

        $this->pausing = null;
        $this->pausingCommand = null;
    }

    public function resume(string $mutexName, ScheduleManager $scheduleManager)
    {
        $scheduleManager->resumeEvent($mutexName);
    }

    #[Layout('schedule-manager::components.layouts.app')]
    public function render(ScheduleManager $scheduleManager)
    {
        $schedules = collect($this->getEvents())
            ->map(fn ($event) => [
                'command' => $event['command'],
                'is_paused' => ! $scheduleManager->shouldRunEvent($event['mutex_name']),
                'pause_until' => $scheduleManager->getPause($event['mutex_name'])?->pauseUntil,
                'mutex_name' => $event['mutex_name'],
            ])
            ->toArray();

        return view('schedule-manager::livewire.schedule-manager', [
            'schedules' => $schedules,
        ]);
    }

    private function getEvents(): array
    {
        $events = Cache::get('schedule-manager::events', []);
        if (empty($events)) {
            \Artisan::call('schedule-manager:optimize');
        }

        return empty($events) ? Cache::get('schedule-manager::events', []) : $events;
    }

    private function getEvent(string $mutexName): array
    {
        return collect($this->getEvents())->first(fn ($event) => $event['mutex_name'] === $mutexName);
    }
}
