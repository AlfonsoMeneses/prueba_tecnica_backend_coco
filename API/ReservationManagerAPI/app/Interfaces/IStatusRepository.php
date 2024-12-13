<?php
namespace App\Interfaces;

interface IStatusRepository{

    public function getAll();
    public function getById(int $id);
    public function getByCode(string $code);
}