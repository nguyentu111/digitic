<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use App\Repositories\Interfaces\ITagRepository;
use App\Repositories\Interfaces\IProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class TagController extends Controller
{
    protected $tagRepository;
    protected $productRepository;
    protected $successCollectionResponse;
    protected $successEntityResponse;
    
    public function __construct(ITagRepository $tagRepository,IProductRepository $productRepository,ISuccessCollectionResponse $successCollectionResponse,
    ISuccessEntityResponse $successEntityResponse){
        $this->tagRepository = $tagRepository;
        $this->successCollectionResponse = $successCollectionResponse;
        $this->successEntityResponse = $successEntityResponse;
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'includes' => 'array',
            'includes.*'=>'in:product'
        ]);
        if($request->has('includes') && in_array('product',$request['includes'])){
            $rs = $this->tagRepository->with('products')->paginate($request['limit'] ?? 10)->toArray();
        }else $rs = $this->tagRepository->paginate($request['limit'] ?? 10)->toArray();
        return $this->successCollectionResponse->createResponse($rs,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
            'collection_id' => 'int',
        ]);
        $rs = $this->tagRepository->create($request->only(['name','collection_id']));
        return  $this->successEntityResponse->createResponse($rs,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = $this->tagRepository->getById($id);
        if(!$tag) throw new ModelNotFoundException('Tag not found for id='.$id.'.');
        return $this->successEntityResponse->createResponse($tag);
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
        $validated = $request->validate([
            'name' => 'string|max:200|unique:tags,name,'.$id,
            'collection_id' => 'int|exists:collections,id',
            // 'product_id'=>'int|exists:products,id'
        ]);
        $res = $this->tagRepository->updateById($request->only(['name','collection_id']),$id);
        return $this->successEntityResponse->createResponse($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = $this->tagRepository->getById($id);
        if(!$tag) throw new ModelNotFoundException('Tag not found for id='.$id.'.');
        $tag->products()->detach();
        $tag->delete();
        return response()->json(["result"=> "ok"]);
    }
}
