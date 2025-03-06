<?php

namespace Zwartpet\ScheduleManager\Commands;

use Cache;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\Models\PausedSchedule;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

class ScheduleOptimize extends Command
{
    use EventPrintable;

    protected $signature = 'schedule-manager:optimize';

    protected $description = 'Cleaning up old pauses';

    public function handle(Schedule $schedule)
    {
        if (config('schedule-manager.driver') === 'database') {
            $this->info('Cleaning up old pauses');
            PausedSchedule::wherePast('pause_until')->delete();
        }

        $events = collect($schedule->events())
            ->map(fn ($event) => [
                'command' => $this->normalizeEvent($event),
                'mutex_name' => $event->mutexName(),
            ])
            ->toArray();

        Cache::put('schedule-manager::events', $events);

        return 0;
    }
}
