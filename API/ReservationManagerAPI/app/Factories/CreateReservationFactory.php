<?php

namespace App\Factories;

use App\Enums\ResourceTypeEnum;
use App\Interfaces\ICreateReservationService;
use App\Interfaces\IReservationRepository;
use App\Services\CreateReservationService;

use App\Exceptions\BusinessException;

class CreateReservationFactory{

    public static function getCreateReservationService(ResourceTypeEnum $resourceType){
        
        switch ($resourceType) {
            case ResourceTypeEnum::GENERAL :
                return new CreateReservationService();            
            default:
                throw new BusinessException("Error interno, no existe modo para registrar la reserva");
        }
    }
}