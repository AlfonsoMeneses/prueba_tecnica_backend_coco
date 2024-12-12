<?php

namespace App\Services;

use Carbon\Carbon;

//Interfaces
use App\Interfaces\IResourceRepository;
use App\Interfaces\IResourceTypeRepository;
use App\Interfaces\IReservationRepository;
use App\Interfaces\IResourceService;
use App\Interfaces\IStatusRepository;

//Excepciones
use App\Exceptions\BusinessException;

//Enums
use App\Enums\ReservationStatusEnum;

//DTOs
use App\DTOs\ResourceDTO;
use App\DTOs\QueryAvailabilityResourceDTO;

class ResourceService implements IResourceService{

    protected $resourceRepository;
    protected $resourceTypeRepository;
    protected $reservationRepository;
    protected $statusRepository;

    public function __construct(IResourceRepository $resourceRepository, 
                                IResourceTypeRepository $resourceTypeRepository,
                                IReservationRepository $reservationRepository,
                                IStatusRepository $statusRepository)
    {
        $this->resourceRepository = $resourceRepository;
        $this->resourceTypeRepository = $resourceTypeRepository;
        $this->reservationRepository = $reservationRepository;
        $this->statusRepository = $statusRepository;
    }

    public function getAll(){
        return $this->resourceRepository->getAll();
    }

    public function getResourceAvailability(QueryAvailabilityResourceDTO $filters){

        try {
            //Obteniendo el estado 'Cancelado"
            $statusCode = ReservationStatusEnum::CANCELLED->value;
            
            $cancelledStatus = $this->statusRepository->getByCode($statusCode);

            //Organizando fechas de rango
            $beginDate = Carbon::parse($filters->beginDate);
            $endDate = Carbon::parse($filters->endDate);

            //Filtro para la consulta
            $filters =[
                "resource_id"   =>  $filters->resourceId,
                "no_cancelled"  =>  $cancelledStatus->id,
                "beginDate"     =>  $beginDate,
                "endDate"       =>  $endDate
            ];

            //Obteniendo posibles recursos activos 
            $reservationValidate = $this->reservationRepository->getReservationByFilters($filters);
            
            //Validando disponibilidad
            if ($reservationValidate->count() > 0) {
                //No disponible
                return false;
            } 
            
            //Disponible
            return true;
        } 
        catch(BusinessException $be){
            throw $be;
        }
        catch (\Exception $ex) {
            throw new BusinessException("Error interno, intente mas tarde");
        }
        

    }

    public function create(ResourceDTO $resource){

        $resourceType = $this->resourceTypeRepository->getById($resource->resource_type_id);

        if ($resourceType != null) {
            return $this->resourceRepository->create($resource);
        }    
        else{
            throw new BusinessException("No existe este tipo de recursos");            
        }
    }
    
}