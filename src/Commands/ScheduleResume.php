<?php

namespace Zwartpet\ScheduleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\ScheduleManager;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

use function Laravel\Prompts\select;

class ScheduleResume extends Command
{
    use EventPrintable;

    protected $signature = 'schedule-manager:resume';

    protected $description = 'Resume a paused scheduled command';

    public function handle(Schedule $schedule, ScheduleManager $scheduleManager): int
    {
        $schedules = collect($schedule->events())
            ->filter(fn (Event $event) => ! $scheduleManager->shouldRunEvent($event))
            ->map(fn (Event $event, $index) => [
                'key' => $index,
                'event' => $event,
                'command' => $this->normalizeEvent($event),
            ]);

        if ($schedules->isEmpty()) {
            $this->info('No paused schedules found');

            return 0;
        }

        $chosenOption = select(
            label: 'Which paused schedule do you want to resume?',
            options: $schedules->pluck('command', 'key')->toArray(),
        );
        $scheduleToResume = $schedules->firstWhere('command', $chosenOption);
        if (! $scheduleToResume) {
            $this->error('Schedule not found');

            return 0;
        }

        $scheduleManager->resumeEvent($scheduleToResume['event']);

        return 0;
    }
}
