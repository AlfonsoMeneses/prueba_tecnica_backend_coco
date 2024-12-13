<?php

namespace App\DTOs;

class ResourceDTO{

    public int $id;
    public string $name;
    public string $description;
    public int $capacity;
    public int $resource_type_id;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->capacity = $data['capacity'];
        $this->resource_type_id = $data['resource_type_id'];
    }

}