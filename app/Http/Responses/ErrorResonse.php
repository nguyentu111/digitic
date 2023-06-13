<?php 
namespace App\Http\Responses;
class ErrorResponse{

    public static function notfound($message){
        return response()->json([
            'result'=>'error',
            'status'=> 404,
            'title'=> "not_found_http_exception",
            'detail'=> $message ?? 'not found !']);
    }
}

?>