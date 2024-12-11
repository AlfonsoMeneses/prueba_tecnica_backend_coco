<?php
namespace App\Services;

use App\Interfaces\IReservationService;
use App\Interfaces\IReservationRepository;

use App\Exceptions\BusinessException;

use App\DTOs\ReservationToCreateDTO;

class ReservationService implements IReservationService{
    
    protected $reservationRepository;

    public function __construct(IReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function create(array $reservation){
        
        return $this->reservationRepository->create($reservation);
    }
}