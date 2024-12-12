<?php
namespace App\Interfaces;

interface IReservationService {
    public function create(array $reservation);
    public function cancel(int $id);
}