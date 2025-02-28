<?php

namespace Zwartpet\ScheduleManager\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Zwartpet\ScheduleManager\ScheduleManager;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

class SchedulePause extends Command
{
    use EventPrintable;

    protected $signature = 'schedule:pause
        {--D|description= : Set a reason for the pause}
        {--U|pause-until= : Set a ttl for the pause}';

    protected $description = 'Pause a scheduled command from running';

    public function handle(Schedule $schedule, ScheduleManager $scheduleManager)
    {
        $schedules = collect($schedule->events())
            ->map(fn (Event $event, $index) => [
                'key' => $index,
                'event' => $event,
                'command' => $this->normalizeEvent($event),
            ]);

        $chosenOption = $this->choice(
            'Which schedules do you want to pause?',
            $schedules->pluck('command', 'key')->toArray()
        );
        $scheduleToPause = $schedules->firstWhere('command', $chosenOption);

        $scheduleManager->pauseEvent(
            $scheduleToPause['event'],
            $this->option('description'),
            $this->hasOption('pause-until') ? Carbon::parse($this->option('pause-until')) : null
        );

        return 0;
    }
}
