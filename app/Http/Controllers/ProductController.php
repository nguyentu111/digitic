<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\Interfaces\IProductRepository;
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
        //
        // $request->validate(['limit'=>'number']);
        // return $this->product_repo->paginate($request['limit'] ?? 10);
        return $this->product_repo->getAll();
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
            'slug'=>'required|max:255'
        ]);
        $rs = Product::create(['name'=>$request['name'], 'branch'=>$request['branch'], 
        'description'=>$request['description'], 'slug'=>$request['slug']]);
        return response($rs,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->product_repo->find(2);
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
