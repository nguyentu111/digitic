<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IProductRepository;
use App\Models\Bank;

class ProductRepository extends BaseRepository implements IProductRepository{
    protected $model_product;

    public function __construct($model_product){
        parent::__construct($model_product);
        $this->model_product = $model_product;
    }
}
