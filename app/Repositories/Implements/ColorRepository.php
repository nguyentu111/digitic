<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IColorRepository;
use App\Models\Order;
class ColorRepository extends BaseRepository implements IColorRepository{
    protected $orderModel;

    public function __construct($orderModel){
        parent::__construct($orderModel);
        $this->orderModel = $orderModel;
    }
  
}
