<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IProductDetailRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Exceptions\CustomException\UnprocessableContent;
class ProductDetailRepository extends BaseRepository implements IProductDetailRepository{
    protected $modelProduct;
    protected $modelProdctDetail;
    protected $modelPicture;
    public function __construct($modelProduct,$modelProdctDetail,$modelPicture){
        parent::__construct($modelProdctDetail);
        $this->modelProduct = $modelProduct;
        $this->modelProdctDetail = $modelProdctDetail;
        $this->modelPicture = $modelPicture;
    }
 
}
