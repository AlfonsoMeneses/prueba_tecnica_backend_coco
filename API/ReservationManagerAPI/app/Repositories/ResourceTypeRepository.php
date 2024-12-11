<?php

namespace App\Repositories;

use App\Interfaces\IResourceTypeRepository;
use App\Exceptions\BusinessException;

use App\Models\ResourceType;

class ResourceTypeRepository implements IResourceTypeRepository{

    public function getAll(){
        return ResourceType::where('active',true)->get();
    }

    public function getById(int $id){
        return ResourceType::where('active',true)->get()->find($id);
    }
}