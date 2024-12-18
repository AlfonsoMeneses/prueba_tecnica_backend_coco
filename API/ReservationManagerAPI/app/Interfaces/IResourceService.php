<?php

namespace App\Interfaces;

use App\DTOs\ResourceDTO;
use App\DTOs\QueryAvailabilityResourceDTO;

interface IResourceService{
    public function getAll();
    public function getAvailableResources(QueryAvailabilityResourceDTO $filters);
    public function create(ResourceDTO $resource);
    public function getResourceAvailability(QueryAvailabilityResourceDTO $filters);
}