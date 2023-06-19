<?php 
namespace App\Repositories\Implements;
use App\Repositories\Interfaces\ISuccessEntityResponse;
class SuccessEntityResponse implements ISuccessEntityResponse{
    public static function createResponse($data,$statusCode = 200){
        return response()->json(['result'=>'ok','response'=>'entity','data'=>$data],$statusCode);
    }
}

?>