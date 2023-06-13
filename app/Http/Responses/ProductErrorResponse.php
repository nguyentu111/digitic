<?php 
namespace App\Http\Responses;

class ProductErrorResponse extends ErrorResponse{
    public static function notfound($message = ''){
        $error = ['status'=> 404,
        'title'=> "not_found_http_exception",
        'detail'=> ''];
        return $self->createResponse();
    }
}

?>