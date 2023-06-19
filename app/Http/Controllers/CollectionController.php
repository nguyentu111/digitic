<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Picture;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CollectionController extends Controller
{
    protected $successCollectionResponse;
    protected $successEntityResponse;
    public function __construct(ISuccessCollectionResponse $successCollectionResponse,
    ISuccessEntityResponse $successEntityResponse){
        $this->successCollectionResponse = $successCollectionResponse;
        $this->successEntityResponse = $successEntityResponse;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'int',
            'page' => 'int'
        ]);
        $rs = Collection::with('picture')->paginate($request->limit ?? 10)->toArray();
        // $rs = $rs->load('picture')->toArray();
        return $this->successCollectionResponse->createResponse($rs);
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
            'name' => 'string|required|max:50|unique:collections,name',
            'picture_id' => 'int|required|exists:pictures,id'
        ]);
        // $url =$request->picture->storeOnCloudinary('digitic')->getSecurePath();
        // $rs = Picture::create(['source'=>$url]);
        $collection = Collection::create(['name'=>$request->name, 'picture_id'=>$request->picture_id]);
        return $this->successEntityResponse->createResponse($collection);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
            'picture_id' => 'exists:pictures,id',
            'name' => 'unique:collections,name,'.$id
        ]);
    
        $collection = Collection::find($id);
        if(!$collection) throw new ModelNotFoundException('Collection not found for id= '.$id.'.');
        $collection->update($request->all());
        return $this->successEntityResponse->createResponse($collection);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Collection::find($id);
        if(!$collection) throw new ModelNotFoundException('Collection not found for id= '.$id.'.');
        $collection->delete();
        return response()->json(['result' => 'ok']);
    }
}
