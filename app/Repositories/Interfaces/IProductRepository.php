<?php
namespace App\Repositories\Interfaces;
use App\Repositories\Interfaces\IBaseRepository;

interface IProductRepository extends IBaseRepository{
    public function createProduct($data);
    public function getAllProduct($data);
    public function updateProduct($data,$id);
    public function showProduct($data,$id);
}
