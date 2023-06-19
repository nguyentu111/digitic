<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\Interfaces\IPictureRepository;
use Illuminate\Support\Facades\DB; 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Exceptions\CustomException\UnprocessableContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Model\Sale;
use Illuminate\Database\Eloquent\Builder;
class ProductRepository extends BaseRepository implements IProductRepository{
    protected $modelProduct;
    protected $modelProdctDetail;
    protected $modelPicture;
    public function __construct($modelProduct,$modelProdctDetail,$modelPicture){
        parent::__construct($modelProduct);
        $this->modelProduct = $modelProduct;
        $this->modelProdctDetail = $modelProdctDetail;
        $this->modelPicture = $modelPicture;
    }
    public function getAllProduct($data){
        $rs = $this->modelProduct;
        $rs = $rs->select('products.id','products.name','regular_price','active')->join('product_details','product_details.product_id','=','products.id')
        ->when(array_key_exists('avalibylities',$data), function (Builder $query, string $role) use ($data){
            $query->where(function (Builder $query) use ($data){
                foreach($data['avalibylities'] as $avai){
                    $query->orWhere('product_details.active', $avai);
                }
            });
        })
        ->when(array_key_exists('sort_by',$data), function (Builder $query, string $role) use ($data){
            switch($data['sort_by']){
                case 'best_selling':
                    $query->selectRaw('min(product_details.regular_price) as price, sum(order_details.quantity) as sold')
                    ->leftJoin('order_details','order_details.product_detail_id','=','product_details.id')
                    ->groupByRaw('products.id,products.name')->orderByRaw('sold desc');
                    break;
                case 'title_ascending':
                    $query->orderBy('products.name')->distinct();
                    break;
                case 'title_descending':
                    $query->orderByDesc('products.name')->distinct();
                    break;
                case 'price_ascending':
                    $query->orderBy('product_details.regular_price')->distinct();
                    break;
                case 'price_descending':
                    $query->orderByDesc('product_details.regular_price');
                    break;
                case 'created_ascending':
                    $query->orderBy('products.created_at');
                    break;
                case 'created_descending':
                    $query->orderByDesc('products.created_at');
                    break;
            }
        } )
        ->when(array_key_exists('colors',$data), function (Builder $query) use ($data){
            $query->join('colors','colors.id','=','product_details.color_id');
            $query->where(function (Builder $query) use ($data){
                foreach($data['colors'] as $color){
                    $query->orWhere('product_details.color_id', $color);
                };
            });
        })
        ->when(array_key_exists('sizes',$data), function (Builder $query) use ($data){
            $query->join('sizes','sizes.product_id','=','products.id');
            $query->where(function (Builder $query) use ($data){
                foreach($data['sizes'] as $size){
                    $query->orWhere('sizes.name', $size);
                };
            });
        })
        ->when(array_key_exists('tags',$data), function (Builder $query) use ($data){
            $query->join('tags','tags.product_id','=','products.id');
            $query->where(function (Builder $query) use ($data){
                foreach($data['tags'] as $tag){
                    // $query->orWhere('sizes.name', $size);
                    // $query->whereExists()
                };
            });
        })
        ->get();
        echo json_encode($rs); 
        return $rs;
        if(array_key_exists('includes',$data)){
            $includes = [];
          foreach($data['includes'] as $include){
            switch($include){
                case 'all':
                    array_push($includes,'tags','details.color','details.picture','pictures','details.sales');
                    break 2;
                case 'tag':
                   array_push($includes,'tags');
                   break;
                case 'sale':
                    array_push($includes,'details.sales');
                    break;
                case 'detail':
                    array_push($includes,'details.color','details.picture');
                    break;
                case 'picture':
                    array_push($includes,'pictures');
                    break;
            }
          }
            $rs = $this->modelProduct->with($includes)->paginate($data['limit'] ?? 10)->withQueryString()->toArray();
        }else{
            $rs = $this->modelProduct->paginate($data['limit'] ?? 10)->withQueryString()->toArray();
        }
    
        // $rs = $this->modelProdctDetail->with('color')->paginate($data['limit'] ?? 10)->toArray();
        return $rs;
    }
    public function createProduct($data){
        $product = DB::transaction(function () use ($data) {
            $product = $this->modelProduct->create(['name'=>$data['name'], 'brand'=>$data['brand'], 
            'description'=>$data['description'], 'slug'=>$data['slug']]);
            if(array_key_exists('tags',$data)){
                foreach($data['tags'] as $tag){
                    if(!is_array($tag)) return throw new UnprocessableContent("Tags must be an array of object.");
                    $product->tags()->attach($tag['id']);
                }
            }
            $pictureAddedUrls = [];

            // foreach($data['pictures'] as $picture){
            //     $url = Cloudinary::upload($picture->getRealPath())->getSecurePath();
            //     $picture = $this->modelPicture->create(['source'=>$url,'product_id'=>$product->id]);
            //     array_push($pictureAddedUrls,$picture->id);
            // }
            // foreach($data['colors'] as $color){
            //     if(!array_key_exists($color['picture']-1,$data['pictures'])) 
            //         throw new UnprocessableContent('Image with index '.$color['picture'].' not exist in uploaded pictures. Total uploaded '.count($data['pictures']).' pictures.');
            //     $uploadedFileUrl =$pictureAddedUrls[$color['picture']-1];
            //     // $result =$data['pictures'][$color['picture']-1] ->storeOnCloudinary('digitic')->getSecurePath();
            //     $pictureId = $pictureAddedUrls[$color['picture']-1];
            //     $product->colors()->attach($color['id'], 
            //     [
            //         'picture_id' => $pictureId,
            //         'regular_price' => $color['regular_price'],
            //         'active' => $color['active'],
            //         'quantity' => $color['quantity'],
            //     ]);
            // }

            return $product;
        });
        return $product;
    }
    public function updateProduct($data,$id){
        $product = $this->getById($id);
        if(!$product) throw new ModelNotFoundException('Product not found for id='.$id.'.');
        $product =  DB::transaction(function () use ($data,$product) {
            // $credentials = $data->only(['name','brand','description','slug']);
            $product->update($data);
            echo json_encode($data);
            if(array_key_exists('tags',$data)){
                $product->tags()->detach();
                foreach($data['tags'] as $tag){
                    $product->tags()->attach($tag['id']);
                }
            }
            return $product;
        });
        return $product;
    }
    public function showProduct($data,$id){
        if(array_key_exists('includes',$data)){
            $includes = [];
          foreach($data['includes'] as $include){
            switch($include){
                case 'all':
                    array_push($includes,'tags','details.color','details.picture','pictures','details.sales');
                    break 2;
                case 'tag':
                   array_push($includes,'tags');
                   break;
                case 'detail':
                    array_push($includes,'details.color','details.picture','details.sales');
                    break;
                case 'picture':
                    array_push($includes,'pictures');
                    break;
            }
          }
            $rs = $this->getById($id);
            if(!$rs ) throw new ModelNotFoundException('Product not found for id = '.$id.'.');
            $rs = $rs->load( $includes);
        }else{
            $rs = $this->getById($id);
            if(!$rs ) throw new ModelNotFoundException('Product not found for id = '.$id.'.');
        }
        // $product = $this->productRepo->getById($id);
        return $rs;
    }
    public function bestSale($builder){
        return $builder->join('product_details','product_details.product_id','=','products.id').groupBy('products.id');
    }
}
