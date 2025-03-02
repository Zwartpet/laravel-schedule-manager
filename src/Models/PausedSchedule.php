<?php

namespace Zwartpet\ScheduleManager\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon $pause_until
 * @property string $pause
 */
class PausedSchedule extends Model
{
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('schedule-manager.drivers.database.table');
    }
}
