<?php

namespace Zwartpet\ScheduleManager\Commands;

use Illuminate\Console\Command;
use Zwartpet\ScheduleManager\Models\PausedSchedule;
use Zwartpet\ScheduleManager\Traits\EventPrintable;

class ScheduleOptimize extends Command
{
    use EventPrintable;

    protected $signature = 'schedule:optimize';

    protected $description = 'Cleaning up old pauses';

    public function handle()
    {
        if (config('schedule-manager.driver') === 'database') {
            $this->info('Cleaning up old pauses');
            PausedSchedule::wherePast('pause_until')->delete();
        } else {
            $this->error('This command only works with the database driver');
        }

        return 0;
    }
}
