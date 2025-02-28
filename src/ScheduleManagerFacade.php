<?php

namespace Zwartpet\ScheduleManager;

use Illuminate\Support\Facades\Facade;

/**
 * @see ScheduleManager
 */
class ScheduleManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'schedule-manager';
    }
}
