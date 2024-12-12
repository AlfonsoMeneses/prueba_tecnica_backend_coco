<?php

namespace App\Repositories;

use App\Interfaces\IResourceRepository;

use App\Models\Resource;

use App\DTOs\ResourceDTO;



class ResourceRepository implements  IResourceRepository{

    public function getAll(){
        return Resource::where('active',true)->get();
    }

    public function getById(int $id){
        $resource = Resource::where([
                                        ['active',true],
                                        ['id',$id]
                                    ])->firstOrFail();
        return $resource;
    }

   

    public function create(ResourceDTO $resource){

        $data = (array)$resource;

        unset($data['id']);

        $newResource = Resource::create($data);

        if ($newResource != null) {
            return $newResource;
        }
        else{
          throw new \Exception("Error interno, intente mas tarde");  
        }        
    }
}