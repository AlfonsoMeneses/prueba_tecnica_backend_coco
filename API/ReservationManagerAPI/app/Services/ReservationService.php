<?php
namespace App\Services;

//Interfaces
use App\Interfaces\IReservationRepository;
use App\Interfaces\IResourceRepository;
use App\Interfaces\IStatusRepository;
use App\Interfaces\IReservationService;

//Exceptions
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\BusinessException;

//Enums
use App\Enums\ReservationStatusEnum;

//DTOs
use App\DTOs\ReservationToCreateDTO;


class ReservationService implements IReservationService{
    
    protected $reservationRepository;
    protected $resourceRepository;
    protected $statusRepository;

    public function __construct(IReservationRepository $reservationRepository,
                                IResourceRepository $resourceRepository,
                                IStatusRepository $statusRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->resourceRepository = $resourceRepository;
        $this->statusRepository = $statusRepository;
    }

    public function create(array $reservation){

        try {
            //Obteniendo datos del recurso
            $resource = $this->resourceRepository->getById($reservation['resource_id'] ?? 0);

            //Obtener Estados Reserva
            $status = $this->statusRepository->getAll();

            if ($status->count() == 0) {
                throw new BusinessException("Error interno, no existen 'Estados'");
            }

            $cancelledStatus = $status->where('code',ReservationStatusEnum::CANCELLED->value)->first();
            $pendingStatus = $status->where('code',ReservationStatusEnum::PENDING->value)->first();

            //Filtros para consultar la disponiblidad del recurso para la reserva
            $filters =[
                "resource_id" => $reservation['resource_id'],
                "no_cancelled" =>$cancelledStatus->id
            ];

            //Obteniendo posibles recursos activos 
            $reservationValidate = $this->reservationRepository->getReservationByFilters($filters);
                        
            //Validando disponibilidad
            if ($reservationValidate->count() > 0) {
                throw new BusinessException("El recurso no esta disponible");
            }

            //Agregando el estado "Pendiente" al registro de la reserva
            $reservation['status_id'] = $pendingStatus->id;

            //Enviando datos de la reserva
            return $this->reservationRepository->create($reservation);

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
}