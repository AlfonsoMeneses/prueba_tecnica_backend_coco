<?php

namespace App\DTOs;

class ReservationToCreateDTO{
    public int $resourceId;
    public date $reservedAt;
    public int $duration;

    public function __construct(array $data)
    {
        $this->resourceId = $data['resourceId'] ?? 0;
        $this->reservedAt = $data['reservedAt'] ?? new date();
        $this->duration = $data['duration'] ?? 0;
    }
}