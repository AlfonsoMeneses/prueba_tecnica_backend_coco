<?php
namespace App\Services;

use Carbon\Carbon;

//Interfaces
use App\Interfaces\IReservationRepository;
use App\Interfaces\IResourceRepository;
use App\Interfaces\IStatusRepository;
use App\Interfaces\IReservationService;
use App\Interfaces\ICreateReservationService;
use App\Interfaces\IResourceTypeRepository;

//Exceptions
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\BusinessException;

//Enums
use App\Enums\ReservationStatusEnum;
use App\Enums\ResourceTypeEnum;

//Factory
use App\Factories\CreateReservationFactory;

//DTOs
use App\DTOs\ReservationToCreateDTO;
use App\DTOs\ReservationToUpdateStatusDTO;


class ReservationService implements IReservationService{
    
    protected $reservationRepository;
    protected $resourceRepository;
    protected $statusRepository;
    protected $resourceTypeRepository;
    
    protected $createReservationService;


    public function __construct(IReservationRepository $reservationRepository,
                                IResourceTypeRepository $resourceTypeRepository,
                                IResourceRepository $resourceRepository,
                                IStatusRepository $statusRepository,
                                )
    {
        $this->reservationRepository = $reservationRepository;
        $this->resourceTypeRepository = $resourceTypeRepository;
        $this->resourceRepository = $resourceRepository;
        $this->statusRepository = $statusRepository;
        
    }

    //Crear reservaciones
    public function create(array $reservation){

        try {
            //Obteniendo datos del recurso
            $resource = $this->resourceRepository->getById($reservation['resource_id'] ?? 0);

            //Obtener Estados Reserva
            $status = $this->getStatus();

            //Estado Cancelado
            $cancelledStatus = $status->where('code',ReservationStatusEnum::CANCELLED->value)->first();

            //Estado Pendiente
            $pendingStatus = $status->where('code',ReservationStatusEnum::PENDING->value)->first();

            //Filtros para consultar la disponiblidad del recurso para la reserva

            //Calculando el rango de fecha para los filtros
            $endDate = Carbon::parse($reservation['reserved_at']);
            $endDate->addHours($reservation['duration']);

            $filters =[
                "resource_id"   =>  $reservation['resource_id'],
                "no_cancelled"  =>  $cancelledStatus->id,
                "beginDate"     =>  $reservation['reserved_at'],
                "endDate"       =>  $endDate
            ];

            //Obteniendo posibles recursos activos 
            $reservationValidate = $this->reservationRepository->getReservationByFilters($filters);
            
            //Validando disponibilidad
            if ($reservationValidate->count() > 0) {
                throw new BusinessException("El recurso no esta disponible");
            }        

            //Agregando el estado "Pendiente" al registro de la reserva
            $reservation['status_id'] = $pendingStatus->id;

            //Obteniendo datos del tipo de recurso
            $resourceType = $this->resourceTypeRepository->getById($resource->resource_type_id);

            $resourceTypeEnum = ResourceTypeEnum::tryFrom($resourceType->code); 

            //Obteniendo el servicio que realizara la reserva
            $this->createReservationService = CreateReservationFactory::getCreateReservationService($resourceTypeEnum);
            
            //Enviando datos de la reserva
            return $this->createReservationService->create($reservation);
            
        } 
        catch (ModelNotFoundException  $mex) {
            throw new BusinessException("Recurso inexistente");
        }
        catch(BusinessException $be){
            throw $be;
        }
        catch(\Exception $ex){
            throw new BusinessException("Error interno, intente mas tarde");
        }
    }

    //Cancelar reservación
    public function cancel(int $id){

        try {

            //Validando la reserva
            $reservation = $this->reservationRepository->getById($id);

            //Si no existe la reserva
            if ($reservation == null) {
                throw new BusinessException("Reserva invalida");   
            }

            //Obtener Estados Reserva
            $status = $this->getStatus();

            //Estado Cancelado
            $cancelledStatus = $status->where('code',ReservationStatusEnum::CANCELLED->value)->first();

            //Estado Pendiente
            $pendingStatus = $status->where('code',ReservationStatusEnum::PENDING->value)->first();

            //Validando estados
            if ($cancelledStatus == null || $pendingStatus == null) {
                throw new BusinessException("Error interno, no existen 'Estados'");
            }

            //Validando el estado de la reserva
            if ($reservation->status_id != $pendingStatus->id) {
                throw new BusinessException("Reserva invalida");
            }

            //
            $reservationToCancel = new ReservationToUpdateStatusDTO([
                'id' =>$id,
                'status_id' => $cancelledStatus->id
            ]);

            //Cancelando la reservación
            return $this->reservationRepository->updateStatus($reservationToCancel);
        } 
        catch(BusinessException $be){
            throw $be;
        }
        catch(\Exception $ex){
            throw new BusinessException("Error interno, intente mas tarde");
        }
    }

    /** Privadas */
    private function getStatus(){
        $status = $this->statusRepository->getAll();

        if ($status->count() == 0) {
            throw new BusinessException("Error interno, no existen 'Estados'");
        }

        return $status;
    }
}