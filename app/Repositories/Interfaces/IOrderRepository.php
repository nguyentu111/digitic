<?php
namespace App\Repositories\Interfaces;
use App\Repositories\Interfaces\IBaseRepository;
use App\Models\Bank;

interface IOrderRepository extends IBaseRepository{

  public function getAllOrder($data);
  public function createOrder($data);
  public function updateOrder($data, $id);
  //  public function getAllOrder($data);
   // public function updateOrder($data,$id);
}
