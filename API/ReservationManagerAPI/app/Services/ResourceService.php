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
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    //Obteniendo todos los recursos activos
    public function getAll(){
        return $this->resourceRepository->getAll();
    }

    //Obteniendo todos los recursos activos y disponibles en un rango de fechas
    public function getAvailableResources(QueryAvailabilityResourceDTO $filters){

        try {
            //Obteniendo el estado 'Cancelado"
            $statusCode = ReservationStatusEnum::CANCELLED->value;
            
            $cancelledStatus = $this->statusRepository->getByCode($statusCode);

            //Organizando fechas de rango
            $beginDate = Carbon::parse($filters->beginDate);
            $endDate = Carbon::parse($filters->endDate);

            //Filtro para la consulta
            $filters =[
                "no_cancelled"  =>  $cancelledStatus->id,
                "beginDate"     =>  $beginDate,
                "endDate"       =>  $endDate
            ];

            //Obteniendo los recursos disponibles 
            return $this->resourceRepository->getAvailableResources($filters);
        } 
        catch(BusinessException $be){
            throw $be;
        }
        catch (\Exception $ex) {
            throw new BusinessException("Error interno, intente mas tarde");
        }
    }

    //Obteniendo la disponibilidad de un recurso por rango de fechas
    public function getResourceAvailability(QueryAvailabilityResourceDTO $filters){

        try {
            //Obteniendo datos del recurso            
            $resource = $this->resourceRepository->getById($filters->resourceId);

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
        catch (ModelNotFoundException  $mex) {
            throw new BusinessException("Recurso inexistente");
        }
        catch(BusinessException $be){
            throw $be;
        }
        catch (\Exception $ex) {
            throw new BusinessException("Error interno, intente mas tarde");
        }      
    }

    //Creación de un recurso
    public function create(ResourceDTO $resource){

        //Obteniendo el tipo de recurso
        $resourceType = $this->resourceTypeRepository->getById($resource->resource_type_id);

        //Validación del tipo de recurso
        if ($resourceType != null) {
            //Creación del recurso
            return $this->resourceRepository->create($resource);
        }
        //Si el tipo de recurso no existe    
        else{
            throw new BusinessException("No existe este tipo de recursos");            
        }
    }
    
}