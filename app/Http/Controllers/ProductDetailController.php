<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Responses\SuccessCollectionResponse;
use App\Http\Responses\SuccessEntityResponse;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\Interfaces\IColorRepository;
use App\Repositories\Interfaces\IProductDetailRepository;
use App\Repositories\Interfaces\ITagRepository;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\CustomException\UnprocessableContent;
use App\Http\Requests\UpdateProductRequest;
class ProductDetailController extends Controller
{
    protected $productRepo;
    protected $productDetailRepo;
    protected $successCollectionResponse;
    protected $successEntityResponse;
    protected $colorRepo;
    
    public function __construct(IProductRepository $productRepo,IProductDetailRepository $productDetailRepo,ISuccessCollectionResponse $successCollectionResponse,
    ISuccessEntityResponse $successEntityResponse,IColorRepository $colorRepo){
        $this->productRepo = $productRepo;
        $this->productDetailRepo = $productDetailRepo;
        $this->successCollectionResponse = $successCollectionResponse;
        $this->successEntityResponse = $successEntityResponse;
        $this->colorRepo = $colorRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'int|exists:products,id|required',
            'color_id' => 'int|exists:colors,id|required',
            'picture_id'=>'int|exists:pictures,id|required',
            'regular_price'=> 'numeric|required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'int|min:0|required',
            'active' => 'boolean|required'
        ]);
        if($this->productDetailRepo->where(['product_id' => $request->product_id, 'color_id' => $request->color_id])->exists()){
            return  throw new  UnprocessableContent('Product already has this color');
        }
        $rs = $this->productDetailRepo->create($request->all());
        return  $this->successEntityResponse->createResponse($rs,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $productDetail = $this->productDetailRepo->find($id)->makeVisible('product_id');   
        if(!$productDetail) return throw new ModelNotFoundException('Product detail not found for id = '.$id.'.');
        $productDetail = $productDetail->load('sales'); 
        // $productDetail->regular_price = number_format($productDetail->regular_price,2);
        return  $this->successEntityResponse->createResponse($productDetail,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'picture_id'=>'int|exists:pictures,id',
            'regular_price'=> 'numeric|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'int|min:0',
            'active' => 'boolean'
        ]);
        $productDetail = $this->productDetailRepo->find($id);   
        if(!$productDetail) return throw new ModelNotFoundException('Product detail not found for id='.$id.'.');
        $rs = $productDetail->update($request->only(['picture_id','regular_price','quantity','active']));
        return response()->json(['result'=>'ok']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productDetail = $this->productDetailRepo->find($id);   
        if(!$productDetail) return throw new ModelNotFoundException('Product detail not found for id='.$id.'.');
        $productDetail->delete();
        return response()->json(['result'=>'ok']);
    }
    
}
