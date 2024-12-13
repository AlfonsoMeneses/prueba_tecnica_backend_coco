<?php

namespace App\Interfaces;

use App\DTOs\ResourceDTO;
use App\DTOs\QueryAvailabilityResourceDTO;

interface IResourceRepository{

    public function getAll();
    public function getById(int $id);
    public function getAvailableResources(array $filters);
    public function create(ResourceDTO $resource);
}

