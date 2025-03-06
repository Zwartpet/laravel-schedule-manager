<?php

namespace Zwartpet\ScheduleManager\Drivers;

use Cache;
use Illuminate\Cache\CacheManager;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Cache\Repository;
use Zwartpet\ScheduleManager\DTO\Pause;

class CacheDriver implements DriverInterface
{
    public function shouldRunEvent(Event|string $event): bool
    {
        return ! $this->getCache()->has($this->getCacheKey($event));
    }

    public function pauseEvent(Event|string $event, Pause $pause): void
    {
        $this->getCache()->put(
            $this->getCacheKey($event),
            json_encode($pause),
            $pause->pauseUntil
        );
    }

    public function resumeEvent(Event|string $event): void
    {
        $this->getCache()->forget($this->getCacheKey($event));
    }

    public function getPause(Event|string $event): ?Pause
    {
        if (! $this->getCache()->has($this->getCacheKey($event))) {
            return null;
        }

        return Pause::fromArray(json_decode($this->getCache()->get($this->getCacheKey($event)), true));
    }

    private function getCacheKey(Event|string $event): string
    {
        return config('schedule-manager.drivers.cache.prefix').(is_string($event) ? $event : $event->mutexName());
    }

    private function getCache(): Repository|CacheManager
    {
        return Cache::store(config('schedule-manager.drivers.cache.store'));
    }
}
