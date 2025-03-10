<?php

namespace Zwartpet\ScheduleManager\Drivers;

class DriverFactory
{
    public static function create(string $driver): DriverInterface
    {
        $driver = ucfirst(strtolower($driver));

        $class = "Zwartpet\\ScheduleManager\\Drivers\\{$driver}Driver";

        if (! class_exists($class)) {
            throw new \InvalidArgumentException("Driver [{$driver}] is not supported.");
        }

        /** @var DriverInterface */
        return new $class;
    }
}
