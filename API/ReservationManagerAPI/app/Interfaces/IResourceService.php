<?php

namespace App\Interfaces;

use App\DTOs\ResourceDTO;

interface IResourceService{
    public function getAll();
    public function create(ResourceDTO $resource);
}