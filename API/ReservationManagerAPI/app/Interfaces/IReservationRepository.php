<?php

namespace App\Interfaces;

use App\DTOs\ReservationToUpdateStatusDTO;

interface IReservationRepository{
    public function getById(int $id);
    public function create(array $reservation);
    public function getReservationByFilters(array $filters);
    public function updateStatus(ReservationToUpdateStatusDTO $reservation);
}