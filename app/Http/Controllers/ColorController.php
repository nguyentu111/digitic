<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Repositories\Interfaces\IColorRepository;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ColorController extends Controller
{
    protected $colorRepo;
    protected $successCollectionResponse;
    protected $successEntityResponse;
    
    public function __construct(IColorRepository $colorRepo,ISuccessCollectionResponse $successCollectionResponse,
    ISuccessEntityResponse $successEntityResponse){
        $this->colorRepo = $colorRepo;
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
        $rs = $this->colorRepo->paginate($request['limit'] ?? 10)->toArray();
        return $rs;
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
            'name'=>'string|min:2|max:50|required|unique:colors,name',
            'hex_value' => 'unique:colors,hex_value|required|regex:/^#[a-zA-Z0-9]{6}$/i',
        ]);
        $color = $this->colorRepo->create($request->all());
        return $this->successEntityResponse->createResponse($color);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $color = $this->colorRepo->getById($id);
        if(!$color) throw new ModelNotFoundException('Color not found for id='.$id.'.');
        return $this->successEntityResponse->createResponse($color);
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
            'name'=>'min:2|max:50|required|unique:colors,name,'.$id,
            'hex_value' => 'regex:/^#[a-zA-Z0-9]{6}$/i',
        ]);
        $color = $this->colorRepo->getById($id);
        if(!$color) throw new ModelNotFoundException('Color not found for id='.$id.'.');
        $color->update($request->all());
        return $this->successEntityResponse->createResponse($color);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = $this->colorRepo->getById($id);
        if(!$color) throw new ModelNotFoundException('Color not found for id='.$id.'.');
        $color->delete();
        return response()->json(['result'=>'ok']);
    }
}
