<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Responses\SuccessCollectionResponse;
use App\Http\Responses\SuccessEntityResponse;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class OrderController extends Controller
{
    protected $orderRepo;
    protected $successCollectionResponse;
    protected $successEntityResponse;
    
    public function __construct(IOrderRepository $orderRepo,ISuccessCollectionResponse $successCollectionResponse,
    ISuccessEntityResponse $successEntityResponse){
        $this->orderRepo = $orderRepo;
        $this->successCollectionResponse = $successCollectionResponse;
        $this->successEntityResponse = $successEntityResponse;
    }



    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $val = $request->validate([
            'includes' => 'array|max:5',
            'includes.*'=>'in:detail,review,all',
            'limit' => 'int',
            'page' => 'int'
        ]);
        $rs = $this->orderRepo->getAllOrder($val);
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
            'paid' => 'boolean|required',
            'user_id' => 'int|exists:users,id',
            // 'status' => ['required', 'string', 'in:canceled,pendding,shipping,shipped'],
            'shipped_at' => ['nullable'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'order_details' => [
                'array',
                'required',
                'min:1'
            ],
            'order_details.*.product_detail_id' => [
                'required','exists:product_details,id',
            ],
            'order_details.*.quantity' => [
                'required',
                'numeric'
            ],
            'order_details.*.regular_price' => 
                'numeric|required|regex:/^\d+(\.\d{1,2})?$/'
            ,
            'order_details.*.sale_price' => 
                'numeric|regex:/^\d+(\.\d{1,2})?$/'
            ,
            'order_details.*.review_id' => ['nullable'],

        ]);
        if (!array_key_exists('review_id', $validated)) {
            $validated['review_id'] = null;
        }
        if (!array_key_exists('shipped_at', $validated)) {
          
            $validated['shipped_at'] = null;
        }
    
        if (!array_key_exists('created_at', $validated)) {
            
            $validated['created_at'] = null;
        }
      
        if (!array_key_exists('updated_at', $validated)) {
         
            $validated['updated_at'] = null;
        }
        $order = $this->orderRepo->createOrder($validated);
        return $this->successEntityResponse->createResponse($order, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->orderRepo->getById($id);
        if (!$order) {
            throw new ModelNotFoundException("Order not found for id = ".$id.'.');
        };
        return $this->successEntityResponse->createResponse($order,200);
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
            'status' => ['required', 'string', 'in:canceled,pendding,shipping,shipped'],
        ]);
        $order = $this->orderRepo->updateOrder((array) $validated, $id);
        return response()->json(["result"=> "ok"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
