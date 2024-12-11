<?php

namespace App\Interfaces;

use App\DTOs\ResourceDTO;

interface IResourceRepository{

    public function getAll();
    public function getById(int $id);
    public function create(ResourceDTO $resource);
}

