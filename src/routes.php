<?php

use Zwartpet\ScheduleManager\Livewire\ScheduleManagerComponent;

Route::get(config('schedule-manager.ui.uri'), ScheduleManagerComponent::class)
    ->middleware([config('schedule-manager.ui.middleware')]);
