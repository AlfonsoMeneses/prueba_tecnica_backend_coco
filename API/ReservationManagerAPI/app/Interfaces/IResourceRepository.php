<?php

namespace App\Interfaces;
use App\DTOs\ResourceDTO;

interface IResourceRepository{

    public function getAll();
    public function create(ResourceDTO $resource);
}
