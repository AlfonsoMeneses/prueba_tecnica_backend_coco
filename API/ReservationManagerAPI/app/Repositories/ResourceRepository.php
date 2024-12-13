<?php

namespace App\Repositories;

//Interfaces
use App\Interfaces\IResourceRepository;

//Modelos
use App\Models\Resource;
use App\Models\Reservation;

//DTOs
use App\DTOs\ResourceDTO;
use App\DTOs\QueryAvailabilityResourceDTO;


class ResourceRepository implements  IResourceRepository{

    //Obteniendo todos los recursos activos
    public function getAll(){
        return Resource::where('active',true)->get();
    }

    //Obteniendo el recurso por ID
    public function getById(int $id){
        $resource = Resource::where(['active' => true,
                                      'id'=> $id
                                    ])->firstOrFail();
        return $resource;
    }

    //Obteniendo los recursos activos y disponibles en un rango de fechas
    public function getAvailableResources(array $filters){
        //Inicio del query
        $query = Reservation::query();

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

        //obteniendo la lista de los recursos reservados
        $resources =  $query->groupBy('resource_id')
                            ->select('resource_id')
                            ->pluck('resource_id');

        
        return Resource::whereNotIn('id', $resources)->get();
    }
   
    //Creación de un recurso
    public function create(ResourceDTO $resource){

        //Convertiendo los datos del objeto en un array
        $data = (array)$resource;

        //Eliminando el ID
        unset($data['id']);

        //Insertando el recurso
        $newResource = Resource::create($data);

        //Validando la creación
        if ($newResource != null) {
            return $newResource;
        }
        else{
          throw new \Exception("Error interno, intente mas tarde");  
        }        
    }
}