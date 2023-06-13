<?php 
namespace App\Http\Responses;
class SuccessCollectionResponse{
    public static function createResponse($data,$statusCode = 200){
        return response()->json(['result'=>'ok','response'=>'collection',...$data],$statusCode);
    }
}

?>