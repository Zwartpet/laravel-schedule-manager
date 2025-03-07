<?php

namespace Zwartpet\ScheduleManager\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\ScheduleManager;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

use function Laravel\Prompts\select;

class SchedulePause extends Command
{
    use EventPrintable;

    protected $signature = 'schedule-manager:pause
        {--D|description= : Set a reason for the pause}
        {--U|pause-until= : Set a ttl for the pause}';

    protected $description = 'Pause a scheduled command from running';

    public function handle(Schedule $schedule, ScheduleManager $scheduleManager): int
    {
        $schedules = collect($schedule->events())
            ->map(fn (Event $event, $index) => [
                'key' => $index,
                'event' => $event,
                'command' => $this->normalizeEvent($event),
            ]);

        $chosenOption = select(
            label: 'Which schedule do you want to pause?',
            options: $schedules->pluck('command', 'key')->toArray(),
        );
        $scheduleToPause = $schedules->firstWhere('command', $chosenOption);
        if (! $scheduleToPause) {
            $this->error('Schedule not found');

            return 0;
        }

        $scheduleManager->pauseEvent(
            $scheduleToPause['event'],
            $this->option('description'), // @phpstan-ignore argument.type
            $this->option('pause-until') ? Carbon::parse($this->option('pause-until')) : null // @phpstan-ignore argument.type
        );

        return 0;
    }
}
