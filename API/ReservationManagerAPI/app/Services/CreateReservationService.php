<?php
namespace App\Services;

//Interfaces
use App\Interfaces\ICreateReservationService;

//Repositorio
use App\Repositories\ReservationRepository;

class CreateReservationService implements ICreateReservationService{
    
    protected $reservationRepository;

    public function __construct()
    {
        $this->reservationRepository = new ReservationRepository();
    }

    public function create(array $reservation){
        try {
            return $this->reservationRepository->create($reservation);
        } 
        catch (\Throwable $th) {
            throw $th;
        }
    }
}