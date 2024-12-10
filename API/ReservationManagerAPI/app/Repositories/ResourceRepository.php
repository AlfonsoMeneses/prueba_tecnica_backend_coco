<?php

namespace App\Repositories;

use App\Interfaces\IResourceRepository;
use App\Models\Resource;
use App\Models\ResourceType;
use Exception;

use App\DTOs\ResourceDTO;

class ResourceRepository implements  IResourceRepository{

    public function getAll(){
        return Resource::where('active',true)->get();
    }

    public function create(ResourceDTO $resource){

        $resourceType = ResourceType::find($resource->resource_type_id);

        if ($resourceType != null) {
            $data = (array)$resource;

            unset($data['id']);

            $newResource = Resource::create($data);

            if ($newResource != null) {
                return $newResource;
            }
            else{
                throw new Exception("Error interno, intente mas tarde");  
            }
        }
        else{
            throw new Exception("No existe este tipo de recursos");            
        }
        
    }
}