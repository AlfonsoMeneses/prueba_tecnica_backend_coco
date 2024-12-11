<?php

namespace App\Repositories;

use App\Interfaces\IStatusRepository;
use App\Exceptions\BusinessException;

use App\Models\Status;

class StatusRepository implements IStatusRepository{

    public function getAll(){
        return Status::where('active',true)->get();
    }

    public function getById(int $id){
        return Status::where(
                                ['active',true],
                                ['id',$id]
                            )->first();
    }
}