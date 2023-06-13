<?php 
namespace App\Http\Responses;
class SuccessEntityResponse{
    public static function createResponse($data, $statusCode = 200){
        return response()->json(['result'=>'ok','response'=>'entity','data'=>$data],$statusCode);
    }
}

?>