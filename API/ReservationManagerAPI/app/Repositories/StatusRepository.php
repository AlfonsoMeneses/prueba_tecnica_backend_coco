<?php

namespace App\Repositories;

use App\Interfaces\IStatusRepository;
use App\Exceptions\BusinessException;

use App\Models\Status;

class StatusRepository implements IStatusRepository{

    //Obteniendo todos los estados
    public function getAll(){
        return Status::where('active',true)->get();
    }

    //Obteniendo estado por ID
    public function getById(int $id){
        return Status::where([
                                'active' => true,
                                'id' => $id
                             ])->first();
    }

    //Obteniendo por código
    public function getByCode(string $code){
        
        return Status::where([
                                'active'=> true,
                                'code' => $code
                             ])->first();
    }
}