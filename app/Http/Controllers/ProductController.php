<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Responses\SuccessCollectionResponse;
use App\Http\Responses\SuccessEntityResponse;
use App\Http\Responses\ErrorResponse;
use App\Repositories\Interfaces\IProductRepository;
use Illuminate\Http\JsonResponse;
class ProductController extends Controller
{
    protected $product_repo;
    
    public function __construct(IProductRepository $product_repo){
        return $this->product_repo = $product_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rs = $this->product_repo->paginate($request['limit'] ?? 10)->toArray();
        return SuccessCollectionResponse::createResponse($rs,200);
       ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $credentials = $request->only([ 'name',
        // 'branch',
        // 'description',
        // 'slug']);
        $validated = $request->validate([
            'name' => 'required|max:200',
            'branch' => 'required|max:50',
            'description'=>'required',
            'slug'=>'required|max:255|unique:products'
        ]);
        $rs = Product::create(['name'=>$request['name'], 'branch'=>$request['branch'], 
        'description'=>$request['description'], 'slug'=>$request['slug']]);
        return  SuccessEntityResponse::createResponse($rs,220);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $product = $this->product_repo->findOrFail($id);
        // if(!$product) return ErrorResponse::createResponse('not found');
        return SuccessEntityResponse::createResponse($product);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
