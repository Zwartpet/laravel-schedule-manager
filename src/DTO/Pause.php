<?php

namespace Zwartpet\ScheduleManager\DTO;

use Carbon\Carbon;
use DateTimeInterface;

class Pause implements \JsonSerializable
{
    public function __construct(
        public ?string $description = null,
        public ?DateTimeInterface $pauseUntil = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['description'],
            $data['pauseUntil'] ? Carbon::parse($data['pauseUntil']) : null,
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'description' => $this->description,
            'pauseUntil' => $this->pauseUntil?->format('Y-m-d H:i:s'),
        ];
    }
}
