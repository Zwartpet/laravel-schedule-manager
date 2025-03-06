<?php

namespace Zwartpet\ScheduleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\ScheduleManager;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

use function Laravel\Prompts\table;

class SchedulePaused extends Command
{
    use EventPrintable;

    protected $signature = 'schedule-manager:paused';

    protected $description = 'Show all paused schedules';

    public function handle(Schedule $schedule, ScheduleManager $scheduleManager)
    {
        $schedules = collect($schedule->events())
            ->filter(fn (Event $event) => ! $scheduleManager->shouldRunEvent($event))
            ->map(fn (Event $event) => [
                $this->normalizeEvent($event),
                $scheduleManager->getPause($event)->pauseUntil?->format('Y-m-d H:i:s') ?? 'Forever',
                $scheduleManager->getPause($event)->description,
            ])
            ->toArray();

        table(['Paused schedule', 'Paused until', 'Description'], $schedules);

        return 0;
    }
}
