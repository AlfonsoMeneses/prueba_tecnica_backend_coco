<?php

namespace App\Interfaces;

interface IReservationRepository{
    public function create(array $reservation);
    public function getReservationByFilters(array $filters);
}