<?php

namespace App\Repositories;

use App\Interfaces\IResourceTypeRepository;
use App\Exceptions\BusinessException;

use App\Models\ResourceType;

class ResourceTypeRepository implements IResourceTypeRepository{

    //Obteniendo los tipos de recurso
    public function getAll(){
        return ResourceType::where('active',true)->get();
    }

    //Obteniendo el tipo de recurso por ID
    public function getById(int $id){
        return ResourceType::where('active',true)->get()->find($id);
    }
}