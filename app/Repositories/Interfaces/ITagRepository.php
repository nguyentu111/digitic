<?php
namespace App\Repositories\Interfaces;
use App\Repositories\Interfaces\IBaseRepository;
use App\Models\Bank;

interface ITagRepository extends IBaseRepository{
    public function updateById(array $data, $id);
}
