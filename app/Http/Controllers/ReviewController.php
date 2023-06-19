<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use App\Repositories\Interfaces\IReviewRepository;
// use App\Exceptions\CustomException\UnprocessableContent;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
class ReviewController extends Controller
{
    protected $reviewRepo, $successCollectionResponse, $successEntityResponse;
    public function __construct(IReviewRepository $reviewRepo,ISuccessCollectionResponse $successCollectionResponse,ISuccessEntityResponse $successEntityResponse  ){
      
        $this->successCollectionResponse =$successCollectionResponse; 
        $this->successEntityResponse = $successEntityResponse; 
        $this->reviewRepo = $reviewRepo; 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'content' => 'string|required',
            'user_id' => 'int|exists:users,id',
            'rating' => ['int','in:1,2,3,4,5', Rule::requiredIf(fn () => !$request->has('parent_id'))],
            'order_detail_id' => 'int|exists:order_details,id|required',
            'parent_id' => 'exists:reviews,id',
            'title' => [ Rule::requiredIf(fn () => !$request->has('parent_id')),'string','max:255'],
        ]);
        $rs = $this->reviewRepo->createReview( $validated);
        return $this->successEntityResponse->createResponse($rs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->reviewRepo->deleteReview($id);
        return response()->json(['result'=>'ok']);
    }

    public function getUserReviews(Request $request,$id){
        $validated = $request->validate([
            'order.created_at' => 'in:desc,asc',
            'includes' => 'array|max:3',
            'includes.*' => 'in:all,order,product',
            'limit' => 'int',
            'page' => 'int'
        ]);
        $rs = $this->reviewRepo->getUserReviews($id,(array) $validated);
        return $this->successCollectionResponse->createResponse($rs);
    }
}
