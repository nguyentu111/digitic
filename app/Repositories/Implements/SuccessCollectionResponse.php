<?php 
namespace App\Repositories\Implements;
use App\Repositories\Interfaces\ISuccessCollectionResponse;
class SuccessCollectionResponse implements ISuccessCollectionResponse{
    public static function createResponse(array $data,$statusCode = 200){
        // return response()->json( array_merge(['result'=>'ok','response'=>'collection'], ['data' => $data]),$statusCode);
        return response()->json(array_merge(['result'=>'ok','response'=>'collection'],$data),$statusCode);
    }
}

?>