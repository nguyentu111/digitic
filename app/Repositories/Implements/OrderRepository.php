<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IOrderRepository;
use Illuminate\Support\Facades\DB; 
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\CustomException\UnprocessableContent;
class OrderRepository extends BaseRepository implements IOrderRepository{
    protected $orderModel, $userModel, $saleModel,$productDetail;

    public function __construct($orderModel, $userModel,$saleModel,$productDetail){
        parent::__construct($orderModel);
        $this->orderModel = $orderModel;
        $this->userModel = $userModel;
        $this->saleModel = $saleModel;
        $this->productDetail = $productDetail;
    }

    public function getAllOrder($data){
        if(array_key_exists('includes',$data)){
            $includes = [];
            foreach($data['includes'] as $include){
                switch($include){
                    case 'all':
                        array_push($includes,'orderDetails','orderDetails.review');
                        break 2;
                    case 'detail':
                        array_push($includes,'orderDetails');
                        break ;
                    case 'review':
                        array_push($includes,'orderDetails.review');
                        break ;
                    default :
                        break;
                }
                
            }
            return $orders = $this->orderModel->with($includes)->paginate($data['limit'] ?? 10)->toArray();
        }
        else return $orders = $this->orderModel->paginate($data['limit'] ?? 10)->toArray();
    }
  
    public function createOrder($data){
     
        $order = DB::transaction(function () use ($data) {
            $order = $this->orderModel->create(['paid' => $data['paid'],'user_id' => $data['user_id'], 'status' => 'pendding']);
           
            foreach($data['order_details'] as $orderDetails)
            { 
                $productDetail = $this->productDetail->find($orderDetails['product_detail_id']) ;
                if($productDetail['regular_price'] != $orderDetails['regular_price']) 
                    return throw new UnprocessableContent('Regular price isn\'t valid');

                if(array_key_exists('sale_price',$orderDetails) && $orderDetails['sale_price'] != $orderDetails['regular_price']){
                    $saleValid = $this->saleModel->saleValid($orderDetails['sale_price'],$orderDetails['product_detail_id'])->first();
                    if(!$saleValid)  return throw new UnprocessableContent('Product sale is over');
                    if($saleValid['sale_price'] != $orderDetails['sale_price'])  
                    return throw new UnprocessableContent('Sale price isn\'t valid');
                    if($saleValid['quantity'] < $orderDetails['quantity']) 
                        return throw new UnprocessableContent('Sale quantity isn\'t enought.');
                }
                $saleValid['quantity']-=  $orderDetails['quantity'];
                $saleValid->update();
                $productDetail['quantity']-= $orderDetails['quantity'];
                $productDetail->update();
                // echo json_encode($saleValid);
                // return null;
                $order->productDetails()->attach(
                  $orderDetails['product_detail_id'],
                    [
                        'regular_price' => $orderDetails['regular_price'],
                        'sale_price' =>array_key_exists('sale_price',$orderDetails) ? $orderDetails['sale_price'] : $orderDetails['regular_price'],
                        'quantity' => $orderDetails['quantity'],
                    ]
                  );
            }    
          
            return $order;
        });
        $order->load('orderDetails');
        return $order;
    }
    
    public function updateOrder($data, $id){
        $order = $this->orderModel->find($id);
        if(!$order) throw new ModelNotFoundException('Order not found for id='.$id.'.');
        $order->update($data);
        return $order;
    }
}
