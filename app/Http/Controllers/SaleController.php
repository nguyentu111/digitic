<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use App\Repositories\Interfaces\ISaleRepository;
use App\Exceptions\CustomException\UnprocessableContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Sale;
use App\Models\ProductDetail;
class SaleController extends Controller
{
    protected $successCollectionResponse;
    protected $successEntityResponse;
    public function __construct(ISuccessCollectionResponse $successCollectionResponse,ISuccessEntityResponse $successEntityResponse  ){
      
        $this->successCollectionResponse =$successCollectionResponse; 
        $this->successEntityResponse = $successEntityResponse; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $sales = Sale::paginate($request['limit'] ?? 10)->toArray();
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
            'from_time' =>  'required|date_format:Y-m-d H:i:s',
            'to_time' => 'required|date_format:Y-m-d H:i:s',
            'product_detail_id' => 'required|int',
            'sale_price' => 'numeric|required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'int|min:0'
        ]);
        $product_detail = ProductDetail::find($request->product_detail_id);
        if(!$product_detail) return throw new ModelNotFoundException('Product detail not found for id = '.$request->product_detail_id.'. ');
        $this->checkValidTime($request->from_time,$request->to_time,$request->product_detail_id);
        if($product_detail['quantity'] < $request->quantity) 
        return throw new UnprocessableContent('sale quantity cannot larger than current quantity.');
        $rs = Sale::create($request->all());
        return $this->successEntityResponse->createResponse($rs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // return  date('Y-m-d H:i:s', time());

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
        $sale = Sale::find($id);
        if(!$sale) return throw new ModelNotFoundException('Sale not found for id = '.$id.'. ');
        $request->validate([
            'from_time' =>  'required|date_format:Y-m-d H:i:s',
            'to_time' => 'required|date_format:Y-m-d H:i:s',
            'sale_price' => 'numeric|required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'int'
        ]);
        $product_detail = ProductDetail::find($sale['product_detail_id']);
        if(!$product_detail) return throw new ModelNotFoundException('Product detail not found for id = '.$request->product_detail_id.'. ');
        $this->checkValidTime($request->from_time,$request->to_time,$sale['product_detail_id'],$id);
        if($product_detail['quantity'] < $request->quantity) 
        return throw new UnprocessableContent('Sale quantity cannot larger than current quantity.');  
       
        $rs = $sale->update($request->only(['from_time','to_time','sale_price','quantity']));
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
        $sale = Sale::find($id);
        if(!$sale) return throw new ModelNotFoundException('Sale not found for id = '.$id.'. ');
        $sale->delete();
        return response()->json(['result'=>'ok']);
    }
    private function checkValidTime($fromTime, $toTime,$productDetailId,$id = null){
      
        if( strtotime($fromTime) > strtotime($toTime)){
            return throw new UnprocessableContent('Sale from time must be less than sale to time.');
        }   
        
        $timeDiff = strtotime($fromTime) - time();
        if($timeDiff / 60 < 0 ) return throw new UnprocessableContent('Cannot set sale time to past.');
        if($timeDiff / 60 < 10 ) return throw new UnprocessableContent('Sale must be set at least 10 minutes before starting.');
        $timeDiff = strtotime($fromTime) - time();
        if($timeDiff / (3600*24) > 30 ) return throw new UnprocessableContent('Sale start time must be in 30 days from now.');
        $timeDiff = strtotime($toTime) - strtotime($fromTime);
        if($timeDiff / (3600*24) > 24 ) return throw new UnprocessableContent('Sale mustn\'t be last longer than 24 days.');
        $sales = Sale::where('product_detail_id',$productDetailId)->get();
        foreach($sales as $sale){
            // echo  $sale['id'] .' '.$id;

            if($id && $sale['id'] == $id){
                continue;
            }   //ignore id updating
            if(strtotime($fromTime) >= strtotime($sale['from_time']) && strtotime($fromTime) <=  strtotime($sale['to_time'])){
                return throw new UnprocessableContent('This time already have a sale.');
            }else if(strtotime($toTime) >= strtotime($sale['from_time']) && strtotime($toTime) <=  strtotime($sale['to_time'])){
                return throw new UnprocessableContent('This time already have a sale.');
            }
        }
    }
}
