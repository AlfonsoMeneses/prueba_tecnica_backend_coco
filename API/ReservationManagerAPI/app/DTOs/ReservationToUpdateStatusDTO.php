<?php

namespace App\DTOs;

class ReservationToUpdateStatusDTO{
    public int $id;
    public int $status_id;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->status_id = $data['status_id'] ?? 0;
    }
}