<?php 
namespace App\Repositories\Interfaces;
interface ISuccessCollectionResponse{
    public static function createResponse(array $data, $statusCode);
}

?>