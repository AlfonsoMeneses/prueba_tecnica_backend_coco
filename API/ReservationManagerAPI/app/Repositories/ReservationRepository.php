<?php
namespace App\Repositories;

use App\Interfaces\IReservationRepository;
use App\Models\Reservation;

class ReservationRepository implements IReservationRepository{
    
    
    public function create(array $reservation){

        $newReservation = Reservation::create($reservation);
        
        return $newReservation;
    }

    public function getReservationByFilters(array $filters){
        
        $query = Reservation::query();

        if(isset($filters['resource_id'])){
            $query->where('resource_id', $filters['resource_id']);
        }

        if(isset($filters['beginDate'])){
            $query->where('reserved_at', '>=', $filters['beginDate']);
        }

        if(isset($filters['endDate'])){
            $query->where('reserved_at', '>=', $filters['endDate']);
        }

        if(isset($filters['no_cancelled'])){
            $query->where('status_id', '!=', $filters['no_cancelled']);
        }

        return $query->get();
    }
}