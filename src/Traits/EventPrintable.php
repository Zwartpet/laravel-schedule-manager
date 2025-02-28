<?php

namespace Zwartpet\ScheduleManager\Traits;

use Illuminate\Console\Scheduling\Event;

trait EventPrintable
{
    public function normalizeEvent(Event $event): string
    {
        return sprintf(
            '%s %s',
            $event->expression,
            $event->normalizeCommand($event->command ?? ''),
        );
    }
}
