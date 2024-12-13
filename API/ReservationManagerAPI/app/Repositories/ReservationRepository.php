<?php
namespace App\Repositories;

use App\Interfaces\IReservationRepository;
use App\Models\Reservation;

use App\DTOs\ReservationToUpdateStatusDTO;

class ReservationRepository implements IReservationRepository{
    
    //Obtener una reserva por ID
    public function getById(int $id){
        return Reservation::find($id);
    }

    //Creando una reserva
    public function create(array $reservation){

        $newReservation = Reservation::create($reservation);
        
        return $newReservation;
    }

    //Consulta de reservas por filtros
    public function getReservationByFilters(array $filters){
        
        //Inicio del query
        $query = Reservation::query();

        //validación por el id del recurso
        if(isset($filters['resource_id'])){
            $query->where('resource_id', $filters['resource_id']);
        }

        //validando si hay reservas no canceladas
        if(isset($filters['no_cancelled'])){
            $query->where('status_id', '!=', $filters['no_cancelled']);
        }

        //Validando el rango de fechas de reservas
        if(isset($filters['beginDate']) && isset($filters['endDate'])){
            $query->where('reserved_at', '>=', $filters['beginDate'])
                  ->where('reserved_at', '<=', $filters['endDate'])
                  ->orWhereRaw("reserved_at + make_interval(hours => duration) >= ? AND reserved_at + make_interval(hours => duration) <= ?", 
                        [$filters['beginDate'], $filters['endDate']])
                  ->orWhereRaw("reserved_at <= ? AND ? <= reserved_at + make_interval(hours => duration)", 
                        [$filters['beginDate'], $filters['beginDate']]);
        
        }
        else if(isset($filters['beginDate'])){
            $query->where('reserved_at', '>=', $filters['beginDate']);
        }
        else if(isset($filters['endDate'])){
            $query->where('reserved_at', '<=', $filters['endDate']);
        }

        //retornando los datos de la consulta.
        return $query->get();
    }

    //Actualizar el estado de una reserva
    public function updateStatus(ReservationToUpdateStatusDTO $reservation){
        //Obteniendo la reserva para actualizar
        $reservationToUpdate = Reservation::find($reservation->id);

        //Si no se encuentra la reserva
        if($reservationToUpdate == null){
            return null;
        }

        //Actualizando el estado y la fecha de actualización
        $reservationToUpdate->status_id = $reservation->status_id;
        $reservationToUpdate->updated_at = now();

        //Guardando cambios
        $reservationToUpdate->save();

        //Enviando datos
        return $reservationToUpdate;
    }
}