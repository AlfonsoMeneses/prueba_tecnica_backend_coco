<?php
namespace App\Interfaces;

use App\DTOs\ResourceDTO;

interface IResourceTypeRepository{

    public function getAll();
    public function getById(int $id);
}