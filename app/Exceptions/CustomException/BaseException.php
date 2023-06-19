<?php 
namespace App\Exceptions\CustomException;
use Exception;
class BaseException extends Exception{
    private $title='unknow_exception';
    private $statusCode = 500;
    public function __construct($message,$title,$statusCode){
        parent::__construct($message);
        $this->title = $title;
        $this->statusCode = $statusCode;
    }
    public function getTitle(){
         return $this->title;   
    }
    public function getStatusCode(){
        return $this->statusCode;
    }
}
?>