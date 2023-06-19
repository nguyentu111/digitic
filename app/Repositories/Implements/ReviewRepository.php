<?php
namespace App\Repositories\Implements;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IReviewRepository;

use App\Exceptions\CustomException\UnprocessableContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Order;
class ReviewRepository extends BaseRepository implements IReviewRepository{
    protected $reviewModel,$orderDetailModel,$userModel;

    public function __construct($reviewModel,$orderDetailModel, $userModel){
        parent::__construct($reviewModel);
        $this->reviewModel = $reviewModel;
        $this->orderDetailModel = $orderDetailModel;
        $this->userModel = $userModel;
    }
    public function createReview($data){
        //ktra them order detail id phai cua user do ko 
        if((!array_key_exists('parent_id',$data)) && $this->reviewModel
        ->where(['user_id'=>$data['user_id'],'order_detail_id'=>$data['order_detail_id']])->exists()){
            echo 'this user has commented once';
            return throw new UnprocessableContent("Each user can only review one time for each product order.");
        }else if(array_key_exists('parent_id',$data)){
            $parentReview = $this->reviewModel->find($data['parent_id']);
            if( $parentReview['parent_id'] !=null) 
                return throw new UnprocessableContent("Cannot nest two level of reviews.");
            return $this->reviewModel->create(['parent_id' => $data['parent_id'],'content'=>$data['content'],
            'user_id' => $data['user_id'], 'order_detail_id'=>$data['order_detail_id']]);
        }else return $this->reviewModel->create($data);
    }
    public function deleteReview($id){

        $review = $this->reviewModel->find($id);
        if(!$review) return throw new ModelNotFoundException("Review not found for id = ".$id.'. ');
        $childReviews = $this->reviewModel->where('parent_id',$id);
        $childReviews->delete();
        $review->delete();
    }
    public function getUserReviews($user_id,array $data){
        $reviews = $this->reviewModel->where(['user_id'=>$user_id, 'parent_id'=>null])
        ->with(['orderDetail.productDetail.picture','responses'])->paginate($data['limit'] ?? 10)->toArray();
        return $reviews;
    }
    
}
