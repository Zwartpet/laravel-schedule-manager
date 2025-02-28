<?php

namespace Zwartpet\ScheduleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\ScheduleManager;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

class ScheduleResume extends Command
{
    use EventPrintable;

    protected $signature = 'schedule:resume';

    protected $description = 'Resume a paused scheduled command';

    public function handle(Schedule $schedule, ScheduleManager $scheduleManager)
    {
        $schedules = collect($schedule->events())
            ->filter(fn (Event $event) => ! $scheduleManager->shouldRunEvent($event))
            ->map(fn (Event $event, $index) => [
                'key' => $index,
                'event' => $event,
                'command' => $this->normalizeEvent($event),
            ]);

        $chosenOption = $this->choice(
            'Which paused schedule do you want to resume?',
            $schedules->pluck('command', 'key')->toArray()
        );
        $scheduleToPause = $schedules->firstWhere('command', $chosenOption);

        $scheduleManager->resumeEvent($scheduleToPause['event']);

        return 0;
    }
}
