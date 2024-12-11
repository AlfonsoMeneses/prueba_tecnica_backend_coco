<?php

namespace App\Services;

use App\Interfaces\IResourceRepository;
use App\Interfaces\IResourceTypeRepository;
use App\Interfaces\IResourceService;

use App\Exceptions\BusinessException;

use App\DTOs\ResourceDTO;

class ResourceService implements IResourceService{

    protected $resourceRepository;
    protected $resourceTypeRepository;

    public function __construct(IResourceRepository $resourceRepository, IResourceTypeRepository $resourceTypeRepository)
    {
        $this->resourceRepository = $resourceRepository;
        $this->resourceTypeRepository = $resourceTypeRepository;
    }

    public function getAll(){
        return $this->resourceRepository->getAll();
    }

    public function create(ResourceDTO $resource){

        $resourceType = $this->resourceTypeRepository->getById($resource->resource_type_id);

        if ($resourceType != null) {
            return $this->resourceRepository->create($resource);
        }    
        else{
            throw new BusinessException("No existe este tipo de recursos");            
        }
    }
    
}