<?php

namespace App\Services;

use App\Interfaces\IResourceRepository;
use App\Interfaces\IResourceService;

use App\DTOs\ResourceDTO;

class ResourceService implements IResourceService{

    protected $resourceRepository;

    public function __construct(IResourceRepository $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function getAll(){
        return $this->resourceRepository->getAll();
    }

    public function create(ResourceDTO $resource){
        return $this->resourceRepository->create($resource);
    }
}