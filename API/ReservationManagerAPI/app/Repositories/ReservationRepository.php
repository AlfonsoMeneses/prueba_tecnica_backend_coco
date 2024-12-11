<?php
namespace App\Repositories;

use App\Interfaces\IReservationRepository;
use App\Models\Reservation;

class ReservationRepository implements IReservationRepository{
    public function create(array $reservation){

        $newReservation = Reservation::create($reservation);

        if ($newReservation != null) {
            return $newReservation;
        }
        else{
          throw new \Exception("Error interno, intente mas tarde");  
        }   
    }
}