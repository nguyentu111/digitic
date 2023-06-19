<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
class SizeController extends Controller
{
    protected $successCollectionResponse;
    protected $successEntityResponse;

    public function __construct(ISuccessCollectionResponse $successCollectionResponse,
    ISuccessEntityResponse $successEntityResponse){
        $this->successCollectionResponse = $successCollectionResponse;
        $this->successEntityResponse = $successEntityResponse;
    }
   
    public function index()
    {
        $sizes = Size::all();

        return  $this->successCollectionResponse->createResponse($sizes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:sizes,name',
            'product_id' => 'required|int|exists:products,id',
        ]);
        $size = Size::create([
            'name' => $request->input('name'),
            'product_id' => $request->input('product_id')
        ]);

        return  $this->successEntityResponse->createResponse($size);
    }

    public function show($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return throw new ModelNotFoundException("Size not found for id = ".$id.'.');
        }

        return $this->successEntityResponse->createResponse($size);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return throw new ModelNotFoundException("Size not found for id = ".$id.'.');
        }

        $size->delete();

        return response()->json(['result'=>'ok']);
    }
}
